<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Kosan Aulia</title>
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .admin-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .admin-menu {
            margin-bottom: 20px;
        }
        .admin-menu ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 20px;
        }
        .admin-menu li {
            display: inline;
        }
        .admin-menu a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .admin-menu a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>Admin - Aulia Kost</h1>
            </div>
            <nav class="nav">
                <a href="{{ route('admin.logout') }}">Logout</a>
            </nav>
        </div>
    </header>

    <div class="admin-container">
       <div class="admin-header">
           <h2>Dashboard Admin</h2>
           <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
               @csrf
               <button type="submit" class="logout-btn">Logout</button>
              </form>
         </div>

         <div class="admin-content">
             <div class="admin-menu">
                 <ul>
                     <li><a href="#manage-rooms">Kelola Kamar</a></li>
                     <li><a href="#manage-bookings">Kelola Booking</a></li>
                     <li><a href="#settings">Pengaturan</a></li>
                 </ul>
                </div>

                <!-- Fitur CRUD taruh disini -->
            </div>
        </div>
</body>
</html>