<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['zipFile'])) {
    // تحديد مجلد التحميل
    $uploadDir = 'uploads/';
    
    // التأكد من وجود المجلد
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // تحديد الملف المرفوع
    $zipFile = $_FILES['zipFile'];
    
    // التحقق من نوع الملف
    $allowedTypes = ['application/zip'];
    if (in_array($zipFile['type'], $allowedTypes)) {
        // تحديد مسار الملف المحفوظ
        $filePath = $uploadDir . basename($zipFile['name']);
        
        // التحقق من وجود الملف مسبقاً
        if (move_uploaded_file($zipFile['tmp_name'], $filePath)) {
            echo "تم رفع الملف بنجاح: " . htmlspecialchars($filePath);
        } else {
            echo "فشل رفع الملف.";
        }
    } else {
        echo "الملف يجب أن يكون من نوع ZIP.";
    }
} else {
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع ملف ZIP</title>
    <style>
        #progressBar {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            height: 30px;
            margin-top: 20px;
        }

        #progressBar div {
            height: 100%;
            width: 0%;
            background-color: #4caf50;
        }

        #status {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>رفع ملف ZIP</h2>
    <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
        <label for="zipFile">اختر ملف ZIP:</label>
        <input type="file" name="zipFile" id="zipFile" accept=".zip" required><br><br>
        <input type="submit" value="رفع الملف">
    </form>

    <div id="progressBar">
        <div></div>
    </div>
    <div id="status"></div>

    <script>
        const form = document.getElementById('uploadForm');
        const progressBar = document.getElementById('progressBar').getElementsByTagName('div')[0];
        const status = document.getElementById('status');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // منع إرسال النموذج بالطريقة التقليدية

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', '', true);

            // تحديث التقدم
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    const percent = (event.loaded / event.total) * 100;
                    progressBar.style.width = percent + '%';
                    status.innerText = 'جاري رفع الملف: ' + Math.round(percent) + '%';
                }
            };

            // عند انتهاء الرفع
            xhr.onload = function() {
                if (xhr.status == 200) {
                    status.innerText = 'تم رفع الملف بنجاح!';
                } else {
                    status.innerText = 'فشل رفع الملف.';
                }
            };

            // إرسال البيانات
            xhr.send(formData);
        });
    </script>
</body>
</html>
<?php
}
?>
