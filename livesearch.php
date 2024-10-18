<?php
include_once("connect.php");

// Lấy tham số tìm kiếm từ URL
$q = isset($_GET["q"]) ? $_GET["q"] : '';

if (strlen($q) > 0) {
    // Truy vấn để tìm kiếm sinh viên theo họ lót, tên sinh viên, hoặc mã sinh viên
    $sql = "SELECT * FROM sinhvien WHERE hoLot LIKE '%$q%' OR tenSV LIKE '%$q%' OR maSV LIKE '%$q%' OR gioiTinh LIKE '%$q%'OR ngaySinh LIKE '%$q%' LIMIT 5";
    $result = $conn->query($sql);

    // Kiểm tra nếu có kết quả
    if ($result->num_rows > 0) {
        // Hiển thị kết quả dưới dạng danh sách liên kết
        while ($row = $result->fetch_assoc()) {
            echo "<div><a href='thongtinsv.php?maSV=" . htmlspecialchars($row['maSV']) . "'>
            " . htmlspecialchars($row['maSV']) . " - " . htmlspecialchars($row['hoLot']) . " " . htmlspecialchars($row['tenSV']) . "
            - " . htmlspecialchars($row['ngaySinh']) . "
            
            </a></div>";

        }
    } else {
        // Nếu không tìm thấy kết quả nào
        echo "Không tìm thấy kết quả nào.";
    }
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
