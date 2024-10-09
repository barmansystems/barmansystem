<!-- Vendor js -->
<script src="/assets/js/vendor.min.js"></script>

<script src="{{ asset('/js/app.js') }}"></script>

<!-- KNOB JS -->
<script src="/assets/libs/jquery-knob/jquery.knob.min.js"></script>
<!-- Apex js-->
{{--<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>--}}

<!-- Plugins js-->
<script src="/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

<!-- Dashboard init-->
{{--<script src="/assets/js/pages/dashboard-sales.init.js"></script>--}}

<script src="/assets/libs/select2/js/select2.min.js"></script>
<script src="{{ asset('/js/app.js') }}"></script>

<!-- App js -->
<script src="/assets/js/app.min.js"></script>

@yield('scripts')
<script src="/assets/js/sweetalert2@11"></script>
<script src="/assets/js/sweetalert.min.js"></script>
@include('sweet::alert')

<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>

    {{-- ajax setup --}}
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    {{-- end ajax setup --}}

    {{-- delete tables row --}}
    $(document).on('click', '.trashRow', function () {
        let self = $(this)
        Swal.fire({
            title: 'حذف شود؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e04b4b',
            confirmButtonText: 'حذفش کن',
            cancelButtonText: 'لغو',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: self.data('url'),
                    type: 'post',
                    data: {
                        id: self.data('id'),
                        _method: 'delete'
                    },
                    success: function (res) {
                        $('tbody:not(.internal_tels)').html($(res).find('tbody:not(.internal_tels)').html());
                        Swal.fire({
                            title: 'با موفقیت حذف شد',
                            icon: 'success',
                            showConfirmButton: false,
                            toast: true,
                            timer: 2000,
                            timerProgressBar: true,
                            position: 'top-start',
                            customClass: {
                                popup: 'my-toast',
                                icon: 'icon-center',
                                title: 'left-gap',
                                content: 'left-gap',
                            }
                        })
                    },
                    error: function (jqXHR, exception) {
                        Swal.fire({
                            title: jqXHR.responseText,
                            icon: 'error',
                            showConfirmButton: false,
                            toast: true,
                            timer: 4000,
                            timerProgressBar: true,
                            position: 'top-start',
                            customClass: {
                                popup: 'my-toast',
                                icon: 'icon-center',
                                title: 'left-gap',
                                content: 'left-gap',
                            }
                        })
                    }
                })

            }
        })
    })
    {{-- end delete tables row --}}

    //  network status
    window.addEventListener("offline", (event) => {
        $('#network_sec').html(`
                <span data-toggle="tooltip" data-placement="bottom" data-original-title="connecting">
                    <i class="fa fa-wifi text-danger zoom-in-out"></i>
                </span>`)
        $('#network_sec span').tooltip();
    });

    window.addEventListener("online", (event) => {
        $('#network_sec').html(`
                <span data-toggle="tooltip" data-placement="bottom" data-original-title="connected">
                    <i class="fa fa-wifi text-success"></i>
                </span>`)
        $('#network_sec span').tooltip();
    });
    // end network status

    // realtime notification
    var audio = new Audio('/audio/notification.wav');
    let userId = "{{ auth()->id() }}"
    Echo.channel('presence-notification.' + userId)
        .listen('SendMessage', (e) => {
            console.log(e)
            $('#notif_count').removeClass('d-none')
            $('#notif_count').html(parseInt($('#notif_count').html()) + 1)
            $("#notif_sec .simplebar-content").prepend(`<a href="/panel/read-notifications/${e.data.id}" class="dropdown-item notify-item active">
                                <div class="notify-icon bg-soft-primary text-primary">
                                    <i class="mdi mdi-comment-account-outline"></i>
                                </div>
                                <p class="notify-details">${e.data.message}
                                    <small class="text-muted">الان</small>
                                </p>
                            </a>`)
            audio.play();
        });
    // end realtime

    // firebase push notification
    var firebaseConfig = {
        apiKey: "AIzaSyDpO8cbXbWPBDvy4-1nbFx1rwfjQCN3Azk",
        authDomain: "barmansystem-cc3f0.firebaseapp.com",
        projectId: "barmansystem-cc3f0",
        storageBucket: "barmansystem-cc3f0.appspot.com",
        messagingSenderId: "278314133950",
        appId: "1:278314133950:web:f52983815cf5187f2831ef"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function (token) {
                // console.log(token);

                console.log(token)

                $.ajax({
                    url: '/panel/saveFcmToken',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        console.log('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error' + err);
                    },
                });

            }).catch(function (err) {
            console.log('User Chat Token Error' + err);
        });
    }

    initFirebaseMessagingRegistration();

    messaging.onMessage(function (payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });

    function myFunction() {
        $.ajax({
            url: '{{route('notifications.check')}}',
            type: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (response) {
                if (response == true) {
                    audio.play();
                }
            },
            error: function (xhr, status, error) {
                console.error('خطا در ارسال درخواست:', error);
            }
        });
    }

    setTimeout(function () {
        myFunction();
        setInterval(myFunction, 60000);
    }, 60000);

</script>

</body>
</html>
