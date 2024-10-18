<?php
// Kết nối đến cơ sở dữ liệu từ file connect.php
include_once("connect.php");

if (isset($_GET['q'])) {
    $q = $_GET['q'];

    $sql = "SELECT * FROM lophoc, sinhvien WHERE sinhvien.maLop = '$q' and lophoc.malop = '$q'" ;
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
    } else {
        echo "Không tìm thấy thông tin lớp học.";
    }
}

$conn->close();
?>