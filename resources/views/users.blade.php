<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body{
            background: #F4F5F6;
        }
        .pagenation-icon span svg{
            width: 22px;
            height: 22px;
        }
        .pagenation-icon nav{
            padding: 0 20px 20px;
        }
        .pagenation-icon nav p{
            margin: 0;
        }
        .pagenation-icon nav > div:first-child{
            display: none;
        }
        .table-con{
            box-shadow: 0rem 0.5rem 2rem 0rem rgba(35, 44, 59, 0.1);
            border: 1px solid #CCCCCC;
            overflow: auto;
            border-radius: 10px;
            margin: 10px 20px 20px;
        }
        .table-con table{
            margin-bottom: 0;
        }
        .table-con img{
            width: 50px;
            height: 50px;    
            object-fit: cover;   
        }
        input[type='file']{
            padding: 15px;
            border: 1px dashed #BBB;
            border-radius: 6px;
        }
        .table-con table tbody tr:hover{
            background: #fff6f8
        }
        .table-con thead{
            
            border-radius: 5px;
        }
        .form-container form{
            margin: 20px 20px 0px;
            padding-bottom: 20px;
            border-bottom: 1px solid #EEE;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .pagenation-icon nav .hidden{
            display: flex;
            justify-content: space-between;
        }
        @media (max-width: 768px){
            .pagenation-icon nav .hidden{
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }
        .pagenation-icon nav .hidden .relative {
            display: flex;
            align-items: center;
        }
        .pagenation-icon nav .hidden .relative span span,
        .pagenation-icon nav .hidden a{
            text-decoration: none;
            padding: 0 !important;;
            aspect-ratio: 1;
            text-decoration: none;
            width: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .pagenation-icon nav .hidden span[aria-current="page"] span{
            color: #FFFFFF;
            background-color: #0d6efd !important;
        }
        .page-title{
            text-align: center;
            margin: 10px auto;
        }
    </style>
</head>


<body>
    <div class="container form-container">
        <form action="/upload" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required accept="xlsx,xls">
            <button type="submit" class="btn btn-secondary" >Upload</button>
        </form>
    </div>
    @if(Request::is('upload'))
        <div class="container form-container">      
            <a href="{{ route('users.list') }}" class="btn btn-success">Users list</a>
        </div>
    @endif
    @if(Request::is('upload'))
        <h3 class="page-title text-primary">Users</h3>
    @else
        <h3 class="page-title text-primary">Imported Users</h3>
    @endif
    
     @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <div class="table-con">
        <table class="table table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th class="text-white">First Name</th>
                    <th class="text-white">Middle Name</th>
                    <th class="text-white">Last Name</th>
                    <th class="text-white">Date of Birth</th>
                    <th class="text-white">Mobile Number</th>
                    <th class="text-white">Father Full Name</th>
                    <th class="text-white">State</th>
                    <th class="text-white">City</th>
                    <th class="text-white">Address</th>
                    <th class="text-white">Profile Photo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->middle_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->date_of_birth }}</td>
                        <td>{{ $user->mobile_number }}</td>
                        <td>{{ $user->father_full_name }}</td>
                        <td>{{ $user->state }}</td>
                        <td>{{ $user->city }}</td>
                        <td>{{ $user->address }}</td>
                        <td>
                            <div class="profile-photo">
                                @if(Request::is('upload'))
                                    <!-- Hide the image for /upload route -->
                                @else
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" width="50">
                                    @else
                                        <span class="text-muted">No profile photo</span>
                                    @endif
                                @endif
                                @if(Request::is('users'))
                                @else
                                    <img id = "uploaded_image_{{ $user->id  }}" style="display: none" src="" alt="Profile Photo" width="50">
                                    <button class="btn btn-primary upload-photo-btn" data-user-id="{{ $user->id }}">Upload</button>
                                @endif
                                <input type="hidden" class="user-id" value="{{ $user->id }}">
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        <div class="pagenation-icon">
            @if ($users->hasPages())
                {{ $users->links() }}
            @endif
        </div>
        
        
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.upload-photo-btn').click(function () {
                var userId = $(this).data('user-id');
                var input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.onchange = function () {
                    var file = this.files[0];
                    if (file) {
                        var formData = new FormData();
                        formData.append('photo', file);
                        formData.append('user_id', userId); // Include the user_id

                        $.ajax({
                            url: '/upload-photo',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                console.log(response);
                                // Update the button text to "Done" and disable it
                                $('.profile-photo .upload-photo-btn[data-user-id="' + userId + '"]')
                                    .hide();
                                    $('#uploaded_image_'+userId).attr('src', response.url).show();

                                    // .prop('disabled', true);
                                // Optionally, update the image displayed immediately
                                $('.profile-photo img[data-user-id="' + userId + '"]').attr('src', URL.createObjectURL(file));
                            },
                            error: function (xhr, status, error) {
                                alert('Error uploading photo: ' + error);
                            },
                        });
                    }
                };
                input.click();
            });
        });
    </script>
</body>

</html>
