<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\PriceHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PDO;

class ProductController extends Controller
{
    /**
     * @var \PDO|null
     */
    private $conn;

    public function index()
    {
        $this->authorize('products-list');

        $products = Product::latest()->paginate(30);
        return view('panel.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('products-create');

        $categories = Category::all();
        return view('panel.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('products-create');

        // create product
        $product = Product::create([
            'title' => $request->title,
            'code' => $request->code,
            'sku' => $request->sku,
            'category_id' => $request->category,
            'system_price' => $request->system_price,
            'partner_price_tehran' => $request->partner_price_tehran,
            'partner_price_other' => $request->partner_price_other,
            'single_price' => $request->single_price,
            'creator_id' => auth()->id(),
        ]);

        // log
        activity_log('create-product', __METHOD__, [$request->all(), $product]);

        alert()->success('محصول مورد نظر با موفقیت ایجاد شد','ایجاد محصول');
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $this->authorize('products-edit');

        return view('panel.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('products-edit');

        // price history
        $this->priceHistory($product, $request);

        // log
        activity_log('edit-product', __METHOD__, [$request->all(), $product]);

        // create product
        $product->update([
            'title' => $request->title,
            'code' => $request->code,
            'sku' => $request->sku,
            'category_id' => $request->category,
            'system_price' => $request->system_price,
            'partner_price_tehran' => $request->partner_price_tehran,
            'partner_price_other' => $request->partner_price_other,
            'single_price' => $request->single_price,
            'creator_id' => auth()->id(),
        ]);

        alert()->success('محصول مورد نظر با موفقیت ویرایش شد','ویرایش محصول');
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->authorize('products-delete');

        if ($product->invoices()->exists()){
            return response('این محصول در سفارشاتی موجود است',500);
        }

        // log
        activity_log('delete-product', __METHOD__, $product);

        $product->delete();
        return back();
    }

    public function search(Request $request)
    {
        $this->authorize('products-list');

        $products = Product::where('title', 'like', "%$request->title%")->when($request->code, function ($query) use ($request) {
            return $query->where('code', $request->code);
        })->latest()->paginate(30);

        return view('panel.products.index', compact('products'));
    }

    public function pricesHistory()
    {
        $this->authorize('price-history');

        $pricesHistory = PriceHistory::latest()->paginate(30);
        return view('panel.prices.history', compact('pricesHistory'));
    }

    public function pricesHistorySearch(Request $request)
    {
        $this->authorize('price-history');

        $products_id = Product::where('title','like', "%$request->title%")->pluck('id');
        $pricesHistory = PriceHistory::whereIn('product_id', $products_id)->latest()->paginate(30);

        return view('panel.prices.history', compact('pricesHistory'));
    }

    public function excel()
    {
        return Excel::download(new \App\Exports\ProductsExport, 'products.xlsx');
    }

    public function parso()
    {
        $this->authorize('parso-products');

        if (\request()->isMethod('get')) {
            return view('panel.products.parso');
        }

        $sku = \request()->sku;
        $title = \request()->title;

        if (!$sku && !$title) {
            alert()->error('لطفا یکی از فیلد های عنوان و یا کد را وارد نمایید','خطا');
            return back();
        }

        try {
            $this->connectToDB();

            $sql = "SELECT pt_posts.id, pt_posts.post_date, pt_posts.post_title, pt_posts.post_status, pt_wc_product_meta_lookup.sku, pt_wc_product_meta_lookup.min_price
            FROM pt_posts
            INNER JOIN pt_wc_product_meta_lookup
                ON pt_posts.id = pt_wc_product_meta_lookup.product_id
            WHERE pt_posts.post_type = 'product' AND pt_posts.post_title LIKE ?";

            $params = ["%$title%"];

            if ($sku) {
                $sql .= " AND pt_wc_product_meta_lookup.sku = ?";
                $params[] = $sku;
            }

            $stmt = $this->conn->prepare($sql);

            foreach ($params as $index => $param) {
                $stmt->bindValue($index + 1, $param);
            }

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $product = $stmt->fetch(PDO::FETCH_OBJ);

        } catch (\Exception $e) {
            return $e->getMessage();
        } finally {
            $this->conn = null;
        }

        return view('panel.products.parso', compact('product'));
    }

    public function parsoUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), ['price' => 'required|numeric']);

        if ($validator->fails()){
            $errors = $validator->errors();
            $product = json_decode($request->product);
            return view('panel.products.parso', compact('errors','product'));
        }

        $product_id = json_decode($request->product)->id;
        $price = $request->price;

        try {
            $this->connectToDB();

            // update in pt_wc_product_meta_lookup
            $sql = "UPDATE pt_wc_product_meta_lookup SET min_price = ? , max_price = ? WHERE product_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(1, $price);
            $stmt->bindValue(2, $price);
            $stmt->bindValue(3, $product_id);
            $stmt->execute();

            // update in pt_postmeta
            $sql2 = "UPDATE pt_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = ?";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bindValue(1, $price);
            $stmt2->bindValue(2, $product_id);
            $stmt2->bindValue(3, '_regular_price');
            $stmt2->execute();

            alert()->success('قیمت محصول مورد نظر با موفقیت تغییر کرد','تغییر قیمت');
            return redirect()->route('parso.index');
        } catch (\Exception $e) {
            return $e->getMessage();
        } finally {
            $this->conn = null;
        }

        return view('panel.products.parso', compact('product'));
    }

    private function priceHistory($product, $request)
    {
        if ($request->system_price != $product->system_price){
            $product->histories()->create([
                'price_field' => 'system_price',
                'price_amount_from' => $product->system_price,
                'price_amount_to' => $request->system_price,
            ]);
        }
        if ($request->partner_price_tehran != $product->partner_price_tehran){
            $product->histories()->create([
                'price_field' => 'partner_price_tehran',
                'price_amount_from' => $product->partner_price_tehran,
                'price_amount_to' => $request->partner_price_tehran,
            ]);
        }
        if ($request->partner_price_other != $product->partner_price_other){
            $product->histories()->create([
                'price_field' => 'partner_price_other',
                'price_amount_from' => $product->partner_price_other,
                'price_amount_to' => $request->partner_price_other,
            ]);
        }
        if ($request->single_price != $product->single_price){
            $product->histories()->create([
                'price_field' => 'single_price',
                'price_amount_from' => $product->single_price,
                'price_amount_to' => $request->single_price,
            ]);
        }
    }

    private function connectToDB()
    {
        $servername = "localhost";
        $username = "parso_tejarat";
        $password = "wrc7QJ9Us";
        $dbname = "parso_tejarat";

        try {
            $this->conn = new \PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

//            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            $message = "Connection failed: " . $e->getMessage();
            dd($message);
        }
    }
}
