<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('tickets-list');

        $url = $request->query('url');

//        dd("test");
        try {
            if (auth()->user()->isAdmin()) {
                $ticketsData = $this->getAllTickets($url);
            } else {
                $ticketsData = $this->getMyTickets($url);
//
            }
//            dd($ticketsData);
            // Check for errors in response
            if (isset($ticketsData['error'])) {
                return response()->json(['error' => $ticketsData['error']], 500);
            }

            // Return view with tickets data
            return view('panel.tickets.index', compact(['ticketsData']));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        $this->authorize('tickets-create');
        $users = $this->getUsers(['user_id' => auth()->id(), 'company' => env('COMPANY_NAME')]);


        return view('panel.tickets.create', compact(['users']));
    }

    public function store(StoreTicketRequest $request)
    {
//        dd($request->All());
        $this->authorize('tickets-create');
//        dd($this->createTicket($request->all()));

//        $ticket = Ticket::create([
//            'sender_id' => auth()->id(),
//            'receiver_id' => $request->receiver,
//            'title' => $request->title,
//            'code' => $this->generateCode(),
//        ]);

        $data = [
            'sender_id' => auth()->id(),
            'company' => env('COMPANY_NAME'),
            'receiver_id' => $request->receiver,
            'title' => $request->title,
            'text' => $request->text,
            'file' => $request->file
        ];
        $ticket = $this->createTicket($data);
        activity_log('create-ticket', __METHOD__, [$request->all(), $ticket]);

//        $ticket->;
//        return $ticket['id'];
        return redirect()->route('tickets.edit', $ticket['id']);
    }

    public function show(Ticket $ticket)
    {
        //
    }

    public function edit($id)
    {
        $this->authorize('tickets-create');
        $ticket = $this->getMessages($id);
//        dd($ticket);
        return view('panel.tickets.edit', compact(['ticket']));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('tickets-create');

        $data = [
            'sender_id' => auth()->id(),
            'text' => $request->text,
            'ticket_id' => $id,
            'file' => $request->file,
            'company' => env('COMPANY_NAME'),
        ];
        $this->chatInTickets($data);
//        dd($ticket->content());
        return back();


    }

    public function destroy($id)
    {
        $this->authorize('tickets-delete');

        $ticket = $this->deleteTicket($id);
//        dd($ticket);

        return back();
    }

    public function changeStatus($id)
    {
        $ticket = $this->changeTicketStatus($id);
        if ($ticket['status'] == 'success') {
            activity_log('ticket-change-status', __METHOD__, $ticket);
            alert()->success('وضعیت تیکت با موفقیت تغییر یافت', 'تغییر وضعیت');
            return back();
        } else {
            abort(403);
        }
    }


    private function getAllTickets($url)
    {
        $apiUrl = $url ?? env('API_BASE_URL') . 'get-all-tickets';

        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }
    private function getMyTickets($url)
    {

        $data = [
            'user_id' => auth()->id(),
            'url' => $url,
            'company' => env('COMPANY_NAME')
        ];
        $apiUrl = $url ?? env('API_BASE_URL') . 'get-my-tickets';
//        dd($apiUrl);

        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post($apiUrl, $data);
            if ($response->successful()) {
//                dd($response->json());
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }
    private function createTicket($data)
    {
        try {
            $httpRequest = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')]);
            if (isset($data['file'])) {
                $file = $data['file'];
                unset($data['file']);
                $httpRequest = $httpRequest->attach('file', file_get_contents($file), $file->getClientOriginalName());
            }

            $response = $httpRequest->post(env('API_BASE_URL') . 'create-ticket', $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request failed', 'details' => $response->body()], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request timed out or failed', 'message' => $e->getMessage()], 500);
        }
    }
    private function getMessages($data)
    {
        $ticket_id = ['ticket_id' => $data];

        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'get-messages', $ticket_id);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }
    private function chatInTickets($data)
    {
        try {
            $httpRequest = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')]);
            if (isset($data['file'])) {
                $file = $data['file'];
                unset($data['file']);
                $httpRequest = $httpRequest->attach('file', file_get_contents($file), $file->getClientOriginalName());
            }

            $response = $httpRequest->post(env('API_BASE_URL') . 'chat-in-tickets', $data);

            if ($response->successful()) {
                $responseData = $response->json();
//                dd($responseData);
                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Request failed', 'details' => $response->body()], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request timed out or failed', 'message' => $e->getMessage()], 500);
        }

    }
    private function getUsers($data)
    {

        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'get-users', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request timed out or failed', 'message' => $e->getMessage()], 500);
        }
    }
    private function deleteTicket($data)
    {
        $ticket_id = ['ticket_id' => $data];

        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'delete-ticket', $ticket_id);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {

            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }
    private function changeTicketStatus($data)
    {
        $data = ['ticket_id' => $data, 'user_id' => auth()->id(), 'company' => env('COMPANY_NAME')];
        try {
            $response = Http::timeout(30)->withHeaders(['API_KEY' => env('API_KEY_TOKEN_FOR_TICKET')])->post(env('API_BASE_URL') . 'change-status-ticket', $data);
            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Request-failed'], $response->status());
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
        }
    }
}
