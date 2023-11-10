<?php

class Database
{
    protected $ketnoi;
    private $host = HOST_DB;
    private $user = USER_DB;
    private $pass = PASS_DB;
    private $dbname = DBNAME_DB;

    /**
     * Kết nối tới cơ sở dữ liệu.
     *
     * @throws Exception nếu có lỗi khi kết nối tới cơ sở dữ liệu
     */
    public function connect()
    {
        try {

            if (!$this->ketnoi) {
                $this->ketnoi = mysqli_connect(
                    $this->host,
                    $this->user,
                    $this->pass,
                    $this->dbname
                );
                mysqli_query($this->ketnoi, "set names 'utf8'");
            }
        } catch (Exception $err) {
            die($err->getMessage());
        }
    }

    /**
     * Tạo bản sao lưu của cơ sở dữ liệu.
     *
     * @return array Kết quả của câu lệnh sao lưu
     */
    public function backup()
    {
        $filename = $this->dbname . date("Y-m-d-H-i-s") . '.sql';
        $command = "mysqldump --opt -h {$this->host} -u {$this->user} -p {$this->pass} --single-transaction >/var/backups/{$filename}";
        exec($command, $output);
        return $output;
    }

    /**
     * Ngắt kết nối với cơ sở dữ liệu nếu có kết nối đang mở.
     *
     * @return void
     */
    public function dis_connect()
    {
        if ($this->ketnoi) {
            mysqli_close($this->ketnoi);
        }
    }

    /**
     * Kết nối đến cơ sở dữ liệu và trả về giá trị của hàng cụ thể dựa trên dữ liệu cho trước.
     *
     * @param mixed $data Dữ liệu được sử dụng để lấy giá trị của hàng cụ thể.
     * @throws Exception Nếu xảy ra lỗi trong quá trình truy vấn cơ sở dữ liệu.
     * @return mixed Giá trị của hàng cụ thể.
     */
    public function site($data)
    {
        $this->connect();
        $row = $this->ketnoi->query("SELECT * FROM `hethong` WHERE ID = '1' ")->fetch_array();
        return $row[$data];
    }

    /**
     * Thực thi một câu truy vấn SQL.
     *
     * @param string $sql Câu truy vấn SQL cần thực thi.
     * @return mixed Kết quả của câu truy vấn.
     */
    public function query($sql)
    {
        $this->connect();
        $row = $this->ketnoi->query($sql);
        return $row;
    }

    /**
     * Cập nhật bảng được chỉ định bằng cách tăng giá trị của một cột cụ thể.
     *
     * @param string $table Tên bảng cần cập nhật.
     * @param string $data Tên cột cần tăng giá trị.
     * @param int $value Giá trị tăng cho cột.
     * @param string $where Điều kiện chỉ định các hàng cần cập nhật.
     * @throws Exception Nếu có lỗi khi thực thi truy vấn.
     * @return int Số hàng bị ảnh hưởng bởi việc cập nhật.
     */
    public function cong($table, $data, $value, $where)
    {
        $this->connect();
        $row = $this->ketnoi->query("UPDATE `$table` SET `$data` = `$data` + '$value' WHERE $where ");
        return $row;
    }

    /**
     * Cập nhật một hàng trong bảng được chỉ định bằng cách trừ đi một giá trị từ một cột cụ thể.
     *
     * @param string $table Tên của bảng.
     * @param string $data Tên của cột cần cập nhật.
     * @param int $value Giá trị cần trừ từ cột.
     * @param string $where Điều kiện để chọn hàng cần cập nhật.
     * @throws Exception Nếu có lỗi khi thực thi câu truy vấn cập nhật.
     * @return int Số hàng bị ảnh hưởng bởi câu truy vấn cập nhật.
     */
    public function tru($table, $data, $value, $where)
    {
        $this->connect();
        $row = $this->ketnoi->query("UPDATE `$table` SET `$data` = `$data` - '$value' WHERE $where ");
        return $row;
    }

    /**
     * Chèn dữ liệu vào bảng được chỉ định.
     *
     * @param string $table Tên của bảng.
     * @param array $data Một mảng kết hợp chứa dữ liệu để chèn.
     * @throws Exception Nếu có lỗi với kết nối cơ sở dữ liệu hoặc truy vấn SQL.
     * @return bool|mysqli_result Trả về true nếu thành công hoặc false nếu thất bại.
     */
    public function insert($table, $data)
    {
        $this->connect();
        $field_list = '';
        $value_list = '';
        foreach ($data as $key => $value) {
            $field_list .= ",$key";
            $value_list .= ",'" . mysqli_real_escape_string($this->ketnoi, $value) . "'"; // mysqli_real_escape_string() để chống sql injection
        }
        $sql = 'INSERT INTO ' . $table . '(' . trim($field_list, ',') . ') VALUES (' . trim($value_list, ',') . ')';

        return mysqli_query($this->ketnoi, $sql);
    }

    /**
     * Cập nhật câu truy vấn SQL và thực thi nó.
     *
     * @param string $sql Câu truy vấn SQL được thực thi.
     * @return mixed Kết quả của câu truy vấn được thực thi.
     */
    public function updateSQL($sql)
    {
        $this->connect();
        return mysqli_query($this->ketnoi, $sql);
    }

    /**
     * Cập nhật một bản ghi trong bảng được chỉ định.
     *
     * @param string $table Tên của bảng cần cập nhật.
     * @param array $data Một mảng kết hợp chứa dữ liệu cần cập nhật.
     * @param string $where Điều kiện để xác định bản ghi cần cập nhật.
     * @return bool|mysqli_result Trả về true nếu thành công hoặc false nếu thất bại.
     */
    public function update($table, $data, $where)
    {
        $this->connect();
        $sql = '';
        foreach ($data as $key => $value) {
            $sql .= "$key = '" . mysqli_real_escape_string($this->ketnoi, $value) . "',";
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where;
        return mysqli_query($this->ketnoi, $sql);
    }

    /**
     * Cập nhật một giá trị trong bảng được chỉ định.
     * @param string $table Tên của bảng.
     * @param array $data Mảng kết hợp chứa dữ liệu cần cập nhật.
     * @param string $where Điều kiện để xác định các dòng cần cập nhật.
     * @param int $value1 Giới hạn số dòng cần cập nhật.
     * @return bool|mysqli_result Kết quả của câu truy vấn cập nhật.
     */
    public function update_value($table, $data, $where, $value1)
    {
        $this->connect();
        $sql = '';
        foreach ($data as $key => $value) {
            $sql .= "$key = '" . mysqli_real_escape_string($this->ketnoi, $value) . "',";
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where . ' LIMIT ' . $value1;
        return mysqli_query($this->ketnoi, $sql);
    }

    /**
     * Xóa các bản ghi từ một bảng được chỉ định dựa trên một điều kiện cho trước.
     *
     * @param string $table Tên của bảng từ đó các bản ghi sẽ được xóa.
     * @param string $where Điều kiện được sử dụng để xác định các bản ghi nào sẽ bị xóa.
     * @return bool|mysqli_result Trả về `true` nếu thành công hoặc `false` nếu thất bại. Nếu truy vấn thành công, một đối tượng `mysqli_result` được trả về.
     */
    public function remove($table, $where)
    {
        $this->connect();
        $sql = "DELETE FROM $table WHERE $where";
        return mysqli_query($this->ketnoi, $sql);
    }

    /**
     * Truy vấn và trả về một danh sách dữ liệu từ cơ sở dữ liệu dựa trên câu truy vấn SQL được cung cấp.
     *
     * @param string $sql Câu truy vấn SQL cần thực hiện.
     * @throws Exception Nếu việc thực thi truy vấn thất bại.
     * @return array Danh sách dữ liệu được truy vấn từ cơ sở dữ liệu.
     */
    public function get_list($sql)
    {
        $this->connect();
        $result = mysqli_query($this->ketnoi, $sql);
        if (!$result) {
            die('Câu truy vấn bị sai');
        }
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }

        // Giải phóng bộ nhớ đã cấp phát cho kết quả truy vấn
        mysqli_free_result($result);
        return $return;
    }

    /**
     * Truy xuất một hàng từ cơ sở dữ liệu dựa trên truy vấn SQL đã cho.
     *
     * @param string $sql Truy vấn SQL để lấy hàng.
     * @throws Exception Nếu truy vấn SQL không hợp lệ hoặc có lỗi khi thực thi nó.
     * @return array|null Mảng kết hợp đại diện cho hàng được truy xuất hoặc null nếu không tìm thấy hàng.
     */
    public function get_row($sql)
    {
        $this->connect();
        $result = mysqli_query($this->ketnoi, $sql);
        if (!$result) {
            die('Câu truy vấn bị sai');
        }
        $row = mysqli_fetch_assoc($result);

        // Giải phóng bộ nhớ đã cấp phát cho kết quả truy vấn
        mysqli_free_result($result);
        return $row;
    }

    /**
     * Hàm xác định số hàng bị ảnh hưởng bởi câu truy vấn.
     *
     * @param string $sql Truy vấn SQL để thực thi.
     * @return int Số hàng bị ảnh hưởng bởi câu truy vấn.
     */
    public function num_rows($sql)
    {
        $this->connect();
        $result = mysqli_query($this->ketnoi, $sql);
        if (!$result) {
            die('Câu truy vấn bị sai');
        }
        $row = mysqli_num_rows($result);

        // Giải phóng bộ nhớ đã cấp phát cho kết quả truy vấn
        mysqli_free_result($result);
        return $row;
    }
}
