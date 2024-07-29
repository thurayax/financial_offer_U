<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض مالي</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            font-weight: 400;
            direction: rtl;
            text-align: right;
            background-color: #f3f4f6;
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
        .vazirmatn-bold {
            font-weight: 700;
        }
        .vazirmatn-extrabold {
            font-weight: 800;
        }
        .vazirmatn-black {
            font-weight: 900;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .no-print {
            display: none;
        }
        .export-mode .bg-blue-100 {
            background-color: #f7fafc;
        }
        .export-mode .text-center,
        .export-mode .text-gray-700,
        .export-mode .text-white {
            color: #4a5568;
        }
        .export-mode .bg-blue-500,
        .export-mode .bg-red-500,
        .export-mode .bg-green-500 {
            background-color: #a0aec0;
        }
        .export-mode .border-gray-300 {
            border-color: #e2e8f0;
        }
        .grayscale {
            filter: grayscale(100%);
        }
        .hidden {
            display: none;
        }
        .info div {
            margin-bottom: 0.5rem;
        }
        .export-mode .info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .export-mode .info div {
            flex: 1 1 45%;
        }
        .export-mode .info div span {
            display: inline-block;
            margin-right: 0.5rem;
        }
        .export-mode .info .swap-order {
            order: -1;
        }
    </style>
</head>
<body>
    <input type="hidden" id="client-id" value="{{ $client->id }}">
    <div class="container" id="content" dir="rtl">
        <div class="bg-blue-100 text-center font-bold p-4 mb-4 vazirmatn-bold">عرض مالي</div>

        <div class="info mb-6">
            <div class="mb-2 vazirmatn-regular">السادة: <span id="client-name">{{ $client->name }}</span></div>
            <div class="mb-2 vazirmatn-regular">التاريخ: <span id="current-date">{{ now()->format('Y-m-d') }}</span></div>
            <div class="vazirmatn-regular">عنوان العميل: <span id="client-address">{{ $client->address }}</span></div>
            <div class="mb-2 vazirmatn-regular hidden" id="unique-id-container">ID: <span id="unique-id"></span></div>
            <div class="mb-2 vazirmatn-regular hidden" id="manager-name-container">اسم المسؤول: <span id="manager-name">{{ $client->manager_name }}</span></div>
        </div>

        <div class="section p-4 rounded bg-white shadow">
            <table class="min-w-full border-collapse" id="service-table">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="border px-4 py-2 vazirmatn-bold">رقم البند</th>
                        <th class="border px-4 py-2 vazirmatn-bold">الخدمة</th>
                        <th class="border px-4 py-2 vazirmatn-bold">سعر الخدمة</th>
                        <th class="border px-4 py-2 vazirmatn-bold">الكمية</th>
                        <th class="border px-4 py-2 vazirmatn-bold">السعر الإجمالي</th>
                    </tr>
                </thead>
                <tbody id="service-table-body">
                    <tr>
                        <td class="border px-4 py-2 vazirmatn-regular">1</td>
                        <td class="border px-4 py-2 vazirmatn-regular">
                            <input type="text" class="w-full border border-gray-300 rounded p-1 vazirmatn-regular" placeholder="أدخل الخدمة">
                            <div class="service-display hidden"></div>
                        </td>
                        <td class="border px-4 py-2 vazirmatn-regular">
                            <input type="number" class="w-full border border-gray-300 rounded p-1 vazirmatn-regular" placeholder="السعر" oninput="calculateTotalPrice()">
                            <div class="price-display hidden"></div>
                        </td>
                        <td class="border px-4 py-2 vazirmatn-regular">
                            <div class="quantity-controls flex justify-center items-center">
                                <button type="button" class="px-2 py-1 bg-gray-200 rounded vazirmatn-regular" onclick="decreaseQuantity(this)">-</button>
                                <span class="quantity mx-2 vazirmatn-regular">1</span>
                                <button type="button" class="px-2 py-1 bg-gray-200 rounded vazirmatn-regular" onclick="increaseQuantity(this)">+</button>
                            </div>
                            <div class="quantity-display hidden"></div>
                        </td>
                        <td class="border px-4 py-2 vazirmatn-regular">100</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-right mt-4 vazirmatn-bold">
                السعر الإجمالي: <span id="total-price">100</span>
            </div>
            <div class="flex justify-between mt-4">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded vazirmatn-bold" onclick="addServiceRow()">إضافة خدمة</button>
                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded vazirmatn-bold" onclick="deleteLastRow()">حذف آخر خدمة</button>
            </div>
        </div>

        <div class="section p-4 mt-6 bg-white shadow rounded">
            <div class="footer bg-blue-100 p-2 text-center vazirmatn-bold">بيان</div>
            <div class="text-right mt-4 vazirmatn-regular">
                <div class="vazirmatn-regular" id="client-statement">{{ $client->statement }}</div>
            </div>
        </div>

        <div class="section p-4 mt-6 bg-white shadow rounded">
            <div class="footer bg-blue-100 p-2 text-center vazirmatn-bold">سياسة الدفع</div>
            <div class="text-right mt-4 vazirmatn-regular">
                <textarea id="payment_policy" name="payment_policy" class="w-full border border-gray-300 rounded p-2 vazirmatn-regular" rows="5" oninput="updatePaymentPolicy()">{{ $client->payment_policy ?? "1. يتم تحويل المبلغ على حسب الدفعات التالية:\n50% كدفعة أولى\n30% كدفعة ثانية\n20% كدفعة ثالثة\n2. يتم تحويل المبلغ إلى حساب جمعية عون التقنية - البنك الأهلي - IBAN SA3310000000200000444207" }}</textarea>
                <div id="payment_policy_display" class="hidden"></div>
            </div>
        </div>
        
        <div class="text-center mt-6">
            <button class="bg-green-500 text-white px-4 py-2 rounded vazirmatn-bold" onclick="exportToPDF()">تصدير إلى PDF</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var today = new Date();
            var date = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');
            document.getElementById('current-date').textContent = date;

            var uniqueId = generateUniqueId();
            document.getElementById('unique-id').textContent = uniqueId;

            calculateTotalPrice();

            var savedPaymentPolicy = localStorage.getItem('paymentPolicy');
            if (savedPaymentPolicy) {
                document.getElementById('payment_policy').value = savedPaymentPolicy;
            }
        });

        function generateUniqueId() {
            var today = new Date();
            var date = today.getFullYear().toString() + (today.getMonth() + 1).toString().padStart(2, '0') + today.getDate().toString().padStart(2, '0');
            var idNumber = parseInt(localStorage.getItem('uniqueIdCounter')) || 0;
            idNumber = (idNumber + 1) % 1000;
            localStorage.setItem('uniqueIdCounter', idNumber);
            var idString = idNumber.toString().padStart(3, '0');
            return date + '-' + idString;
        }

        function increaseQuantity(button) {
            var quantityElement = button.parentNode.querySelector('.quantity');
            var quantity = parseInt(quantityElement.textContent);
            quantity++;
            quantityElement.textContent = quantity;
            calculateTotalPrice();
        }

        function decreaseQuantity(button) {
            var quantityElement = button.parentNode.querySelector('.quantity');
            var quantity = parseInt(quantityElement.textContent);
            if (quantity > 1) {
                quantity--;
                quantityElement.textContent = quantity;
                calculateTotalPrice();
            }
        }

        function addServiceRow() {
            var tableBody = document.getElementById('service-table-body');
            var newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="border px-4 py-2 vazirmatn-regular">${tableBody.rows.length + 1}</td>
                <td class="border px-4 py-2 vazirmatn-regular">
                    <input type="text" class="w-full border border-gray-300 rounded p-1 vazirmatn-regular" placeholder="أدخل الخدمة">
                    <div class="service-display hidden"></div>
                </td>
                <td class="border px-4 py-2 vazirmatn-regular">
                    <input type="number" class="w-full border border-gray-300 rounded p-1 vazirmatn-regular" oninput="calculateTotalPrice()">
                    <div class="price-display hidden"></div>
                </td>
                <td class="border px-4 py-2 vazirmatn-regular">
                    <div class="quantity-controls flex justify-center items-center">
                        <button type="button" class="px-2 py-1 bg-gray-200 rounded vazirmatn-regular" onclick="decreaseQuantity(this)">-</button>
                        <span class="quantity mx-2 vazirmatn-regular">1</span>
                        <button type="button" class="px-2 py-1 bg-gray-200 rounded vazirmatn-regular" onclick="increaseQuantity(this)">+</button>
                    </div>
                    <div class="quantity-display hidden"></div>
                </td>
                <td class="border px-4 py-2 vazirmatn-regular">100</td>
            `;
            tableBody.appendChild(newRow);
            calculateTotalPrice();
        }

        function deleteLastRow() {
            var tableBody = document.getElementById('service-table-body');
            if (tableBody.rows.length > 1) {
                tableBody.deleteRow(tableBody.rows.length - 1);
                calculateTotalPrice();
            }
        }

        function calculateTotalPrice() {
            var tableBody = document.getElementById('service-table-body');
            var totalPrice = 0;
            for (var i = 0; i < tableBody.rows.length; i++) {
                var row = tableBody.rows[i];
                var quantity = parseInt(row.querySelector('.quantity').textContent);
                var pricePerItem = parseInt(row.cells[2].querySelector('input').value);
                var totalRowPrice = quantity * pricePerItem;
                row.cells[4].textContent = totalRowPrice;
                totalPrice += totalRowPrice;
            }
            document.getElementById('total-price').textContent = totalPrice;
        }

        function updatePaymentPolicy() {
            var client_id = document.getElementById('client-id').value;
            var payment_policy = document.getElementById('payment_policy').value;

            fetch(`/offer/${client_id}/update-payment-policy`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ payment_policy: payment_policy })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('paymentPolicy', payment_policy);
                } else {
                    alert('Failed to update payment policy');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update payment policy');
            });
        }

        function exportToPDF() {
            // جمع البيانات من الجدول
            var offers = [];
            var rows = document.querySelectorAll('#service-table-body tr');
            rows.forEach(function(row) {
                var service = row.querySelector('input[type="text"]').value;
                var service_price = parseFloat(row.querySelector('input[type="number"]').value);
                var quantity = parseInt(row.querySelector('.quantity').textContent);
                var total_price = parseFloat(row.cells[4].textContent);

                offers.push({
                    service: service,
                    service_price: service_price,
                    quantity: quantity,
                    total_price: total_price
                });
            });

            var client_id = document.getElementById('client-id').value;

            // إرسال البيانات إلى السيرفر
            fetch('/offer/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    client_id: client_id,
                    offers: offers
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('unique-id-container').classList.remove('hidden');
                    document.getElementById('manager-name-container').classList.remove('hidden');

                    // إخفاء الأزرار وتطبيق النمط الأبيض والأسود وتغيير الألوان
                    document.querySelectorAll('button').forEach(el => el.classList.add('no-print'));
                    document.body.classList.add('grayscale', 'export-mode');

                    // نسخ محتوى textarea إلى div  لتحسين التنسيق
                    var paymentPolicy = document.getElementById('payment_policy').value;
                    document.getElementById('payment_policy_display').innerHTML = paymentPolicy.replace(/\n/g, '<br>');
                    document.getElementById('payment_policy_display').classList.remove('hidden');
                    document.getElementById('payment_policy').classList.add('hidden');

                    
                    rows.forEach(function(row) {
                        var serviceInput = row.querySelector('input[type="text"]');
                        var serviceDisplay = row.querySelector('.service-display');
                        serviceDisplay.textContent = serviceInput.value;
                        serviceDisplay.classList.remove('hidden');
                        serviceInput.classList.add('hidden');

                        var priceInput = row.querySelector('input[type="number"]');
                        var priceDisplay = row.querySelector('.price-display');
                        priceDisplay.textContent = priceInput.value;
                        priceDisplay.classList.remove('hidden');
                        priceInput.classList.add('hidden');

                        var quantityElement = row.querySelector('.quantity');
                        var quantityDisplay = row.querySelector('.quantity-display');
                        quantityDisplay.textContent = quantityElement.textContent;
                        quantityDisplay.classList.remove('hidden');
                        quantityElement.classList.add('hidden');
                    });

                    // تعديل ترتيب العناصر عند التصدير
                    document.querySelector('.info').classList.add('export-mode');

                    // تحويل HTML إلى PDF
                    var element = document.getElementById('content');
                    html2pdf().from(element).set({
                        margin: 1,
                        filename: 'عرض_مالي.pdf',
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                    }).toPdf().get('pdf').then(function (pdf) {
                        
                        document.querySelectorAll('button').forEach(el => el.classList.remove('no-print'));
                        document.body.classList.remove('grayscale', 'export-mode');
                        document.getElementById('unique-id-container').classList.add('hidden');
                        document.getElementById('manager-name-container').classList.add('hidden');
                        document.getElementById('payment_policy_display').classList.add('hidden');
                        document.getElementById('payment_policy').classList.remove('hidden');

                        rows.forEach(function(row) {
                            var serviceInput = row.querySelector('input[type="text"]');
                            var serviceDisplay = row.querySelector('.service-display');
                            serviceInput.classList.remove('hidden');
                            serviceDisplay.classList.add('hidden');

                            var priceInput = row.querySelector('input[type="number"]');
                            var priceDisplay = row.querySelector('.price-display');
                            priceInput.classList.remove('hidden');
                            priceDisplay.classList.add('hidden');

                            var quantityElement = row.querySelector('.quantity');
                            var quantityDisplay = row.querySelector('.quantity-display');
                            quantityElement.classList.remove('hidden');
                            quantityDisplay.classList.add('hidden');
                        });

                    
                        document.querySelector('.info').classList.remove('export-mode');

                        pdf.save('document.pdf');
                    });
                } else {
                    alert('Failed to save the offer');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save the offer');
            });
        }
    </script>
</body>
</html>
