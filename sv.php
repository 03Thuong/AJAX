<?php
session_start();
include_once("connect.php");


// Viết câu truy vấn để lấy danh sách sinh viên
$sql = "SELECT * FROM sinhvien, lophoc WHERE sinhvien.maLop = lophoc.maLop";
$result = $conn->query($sql);

// Khởi tạo biến để lưu sinh viên vừa thêm
$addedStudent = '';
if (isset($_GET['addedStudent'])) {
    $addedStudent = urldecode($_GET['addedStudent']);
}

// Xử lý đăng xuất
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>QUẢN LÝ SINH VIÊN</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #FFEFD5;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            max-width: 1100px;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
        }

        .btn-primary {
            width: 120px;
            margin: 0 auto 20px auto;
            padding: 5px 10px;
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table th {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            text-align: center;
        }

        table td {
            padding: 12px;
            text-align: center;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-edit,
        .btn-delete {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #fff;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .text-center {
            text-align: center;
        }

        .search-button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            margin-left: 10px;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            display: block;
            margin: 30px auto 0 auto;
            width: 150px;
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .logout-btn:hover {
            background-color: #0056b3;
            ;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .search-input {
            width: 600px;
            padding: 5px;
            border: 1px solid #007bff;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 0;
            /* Loại bỏ khoảng cách dưới */
            box-sizing: border-box;
            /* Đảm bảo padding và border nằm trong kích thước */
        }

        .livesearch {
            display: none;
            position: absolute;
            z-index: 1;
            width: 600px;
            top: calc(100% + 10px);
            max-height: 200px;
            overflow-y: auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #007bff;
            border-radius: 5px;
            margin-top: 0;
            box-sizing: border-box;
            margin-right: 120px;
        }
    </style>
</head>

<body>

    <div class="container">
            <h2>DANH SÁCH SINH VIÊN</h2>

            <!-- AJAX-----Form tìm kiếm sinh viên -->
            <form method="GET" class="mb-3">
                <div class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Tìm kiếm sinh viên..."
                        onkeyup="showResult(this.value)"
                        >
                    <div id="livesearch" class="livesearch"></div> <!-- Đảm bảo class livesearch -->
                    <button class="search-button"><i class="fa fa-search"></i> Tìm kiếm</button>
                </div>

            </form>


            <?php
            // Lấy giá trị tìm kiếm
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            // Truy vấn danh sách sinh viên
            $sql = "SELECT * FROM sinhvien, lophoc WHERE sinhvien.maLop = lophoc.maLop";

            if ($search) {
                $sql .= " AND (sinhvien.hoLot LIKE '%$search%' OR sinhvien.tenSV LIKE '%$search%' OR sinhvien.maSV LIKE '%$search%' OR sinhvien.gioiTinh LIKE '%$search%')";
            }
            // Hiển thị thông báo khi thêm sinh viên thành công
            if (!empty($addedStudent)) {
                echo "<div class='alert alert-success'>Sinh viên <strong>$addedStudent</strong> đã được thêm thành công!</div>";
            }

            // Thiết lập phân trang
            $limit = 5; // Số sinh viên trên mỗi trang
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Đếm tổng số sinh viên
            $count_sql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
            $count_result = $conn->query($count_sql);
            $total_students = $count_result->fetch_assoc()['total'];
            $total_pages = ceil($total_students / $limit);

            // Thêm phân trang vào truy vấn
            $sql .= " LIMIT $limit OFFSET $offset";
            $result = $conn->query($sql);



            // Hiển thị danh sách sinh viên
            if ($result->num_rows > 0) {
                echo "<table class='table table-hover table-bordered mt-3'>";
                echo "<thead><tr><th>Mã SV</th><th>Họ Lót</th><th>Tên SV</th><th>Ngày Sinh</th><th>Giới Tính</th><th>Mã Lớp</th><th>Sửa</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='thongtinsv.php?maSV=" . $row["maSV"] . "'>" . $row["maSV"] . "</a></td>";
                    echo "<td>" . $row["hoLot"] . "</td>";
                    echo "<td>" . $row["tenSV"] . "</td>";
                    echo "<td>" . $row["ngaySinh"] . "</td>";
                    echo "<td>" . $row["gioiTinh"] . "</td>";
                    echo "<td>" . $row["maLop"] . " - " . $row["tenLop"] . "</td>";
                    echo "<td><a class='btn btn-edit' href='sua_sv.php?ma=" . $row["maSV"] . "'><i class='fa fa-edit'></i></a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";

                // Phân trang
                echo "<nav class='mt-3'>";
                echo "<ul class='pagination justify-content-center'>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = $i == $page ? 'active' : '';
                    echo "<li class='page-item $active'><a class='page-link' href='?page=$i&search=$search'>$i</a></li>";
                }
                echo "</ul>";
                echo "</nav>";
            } else {
                echo "<p class='text-center'>Không có sinh viên nào được tìm thấy.</p>";
            }

            $conn->close();
            ?>

            <!-- Nút đăng xuất -->
            <form method="POST">
                <div class="text-center">
                    <button type="submit" name="logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </div>
            </form>
    </div>

    <script>
        // Ẩn thông báo sau 5 giây
        setTimeout(function () {
            var alertBox = document.querySelector('.alert');
            if (alertBox) {
                alertBox.style.display = 'none'; // Ẩn thông báo
            }
        }, 5000);
    </script>
      <!-- AJAX -->
    <script>
        function showResult(str) {
            if (str.length == 0) {
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px";
                document.getElementById("livesearch").style.display = "none"; // Ẩn theo mặc định
                return;
            }
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("livesearch").innerHTML = this.responseText;
                    document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
                    document.getElementById("livesearch").style.display = "block"; // Hiển thị khi có kết quả
                }
            }
            xmlhttp.open("GET", "livesearch.php?q=" + str, true);
            xmlhttp.send();
        }
         
    </script>

</body>

</html>