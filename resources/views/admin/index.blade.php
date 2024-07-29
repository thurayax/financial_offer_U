<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة الإدارة</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 400;
            direction: rtl;
            text-align: right;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .vazirmatn-extralight {
            font-weight: 200;
        }
        .vazirmatn-light {
            font-weight: 300;
        }
        .vazirmatn-regular {
            font-weight: 400;
        }
        .vazirmatn-medium {
            font-weight: 500;
        }
        .vazirmatn-semibold {
            font-weight: 600;
        }
        .vazirmatn-bold {
            font-weight: 700;
        }
        .vazirmatn-extrabold {
            font-weight: 800;
        }
        .vazirmatn-black {
            font-weight: 900;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="bg-blue-100 text-center font-bold p-4 mb-4 vazirmatn-bold">صفحة الإدارة</div>

        <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-xl font-bold mb-4 vazirmatn-bold">إدخال بيانات العميل</h2>
            <form action="{{ route('admin.saveClient') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="client-name" class="block mb-2 vazirmatn-regular">السادة:</label>
                    <input type="text" id="client-name" name="client_name" class="w-full p-2 border border-gray-300 rounded vazirmatn-regular">
                </div>
                <div class="mb-4">
                    <label for="client-address" class="block mb-2 vazirmatn-regular">عنوان العميل:</label>
                    <input type="text" id="client-address" name="client_address" class="w-full p-2 border border-gray-300 rounded vazirmatn-regular">
                </div>
                <div class="mb-4">
                    <label for="client-statement" class="block mb-2 vazirmatn-regular">البيان:</label>
                    <textarea id="client-statement" name="client_statement" class="w-full p-2 border border-gray-300 rounded vazirmatn-regular"></textarea>
                </div>
                <div class="mb-4">
                    <label for="manager-name" class="block mb-2 vazirmatn-regular">اسم المسؤول:</label>
                    <input type="text" id="manager-name" name="manager_name" class="w-full p-2 border border-gray-300 rounded vazirmatn-regular">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded vazirmatn-bold">حفظ البيانات</button>
            </form>
        </div>

        <div class="bg-white p-4 rounded shadow-md mt-6">
            <h3 class="text-lg font-bold mb-4 vazirmatn-bold">قائمة العملاء</h3>
            <table class="min-w-full bg-white border border-gray-200 vazirmatn-regular">
                <thead class="bg-blue-100 vazirmatn-bold">
                    <tr>
                        <th class="py-2 px-4 border-b">السادة</th>
                        <th class="py-2 px-4 border-b">اسم المسؤول</th>
                        <th class="py-2 px-4 border-b">تعديل</th>
                        <th class="py-2 px-4 border-b">حذف</th>
                    </tr>
                </thead>
                <tbody id="user-table">
                    @foreach ($clients as $client)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $client->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $client->manager_name }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <a href="{{ route('admin.editClient', $client->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded vazirmatn-bold">تعديل</a>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <form action="{{ route('admin.deleteClient', $client->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded vazirmatn-bold">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
