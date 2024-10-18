<?php
 // Kết nối cơ sở dữ liệu từ file connect.php
 include_once("connect.php");
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>QUẢN LÝ THÔNG TIN LỚP HỌC</title>
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
      max-width: 1200px;
    }

    h2 {
      text-align: center;
      color: #007bff;
      margin-bottom: 30px;
    }

    .btn-primary {
      width: 120px; /* Điều chỉnh chiều rộng phù hợp */
      margin: 0 auto 20px auto;
      padding: 5px 10px;
      font-size: 16px;
      font-weight: bold;
    }

    .logout-btn {
      width: 120px; /* Kích thước tương tự nút Thêm mới */
      padding: 5px 10px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      margin-left: 30px;
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

    .btn-edit, .btn-delete {
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
    .group {
      text-align: center;
    }
    .form-group {
      text-align: center;
      width: 600px;
      margin: 0 auto;
    }
  </style>
   <!-- AJAX -->
  <script>
function showClassInfo(str) {
    if (str == "") {
        document.getElementById("classInfo").innerHTML = "";
        document.getElementById("classTable").style.display = "block"; // Hiển thị bảng khi không chọn lớp
     
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("classInfo").innerHTML = this.responseText;
                document.getElementById("classTable").style.display = "none"; // Ẩn bảng khi chọn lớp
                document.getElementById("backButton").style.display = "inline-block"; // Hiện nút Quay lại
            }
        };
        xmlhttp.open("GET", "ajax_get_class_info.php?q=" + str, true); // Thay đổi đường dẫn tới file xử lý
        xmlhttp.send();
    }
}


</script>

</head>
<body>

<div class="container">
  <h2>QUẢN LÝ THÔNG TIN LỚP HỌC</h2>

  <!-- AJAX --- from input--- -->
  <div class="form-group">
    <select id="classSelect" class="form-control" onchange="showClassInfo(this.value)">
      <option value="">Chọn một lớp học:.........</option>
      <?php
          $sql = "SELECT * FROM lophoc";
          $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
          echo "<option value='" . $row['maLop'] . "'>" .$row['maLop']. '-' .$row['tenLop'] . "</option>";
        }
      ?>
    </select>
  </div>

  <div id="classInfo" class="mt-3"></div>
  <div id="classTable">
  <?php
    // Hiển thị bảng thông tin lớp học
    $sql = "SELECT * FROM lophoc";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo "<table class='table table-hover table-bordered mt-3'>";
      echo "<thead><tr><th>Mã lớp</th>
              <th>Tên lớp</th>
              <th>Ghi chú</th>
              <th>Sửa</th>
              <th>Xóa</th>
              </tr></thead>";
      echo "<tbody>";
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a href='sinhvien.php?maLop=" . $row["maLop"] . "'>" . $row["maLop"] . "</a></td>";
        echo "<td>" . $row["tenLop"] . "</td>";
        echo "<td>" . $row["ghiChu"] . "</td>";
        echo "<td><a class='btn btn-edit' href='sua_lop.php?ma=" . $row["maLop"] . "'><i class='fa fa-edit'></i></a></td>";
        echo "<td><a class='btn btn-delete' href='xoa_lop.php?ma=" . $row["maLop"] . "'><i class='fa fa-trash'></i></a></td>";
        echo "</tr>";
      }
      echo "</tbody></table>";
    } else {
      echo "<p class='text-center'>Không có lớp học nào được tìm thấy.</p>";
    }
    $conn->close();
  ?>
  </div>
  
  <div class="group">
    <a class="btn btn-primary" href="form_lop.php">
        <i class="fa fa-plus"></i> Thêm mới
    </a>
    <a class="btn btn-primary logout-btn" href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Đăng xuất
    </a>
    <a id="backButton" class="btn btn-primary" href="lophoc.php" style="margin-left: 20px; display: none;">
        <i class="fa fa-undo"></i> Quay lại</a>
  </div>
</div>

</body>
</html>
