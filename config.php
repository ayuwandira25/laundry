<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$db = mysqli_connect("localhost", "root", "", "kykalaundry");

if (!$db) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function tableExists($table)
{
    global $db;
    $table = mysqli_real_escape_string($db, $table);
    $result = mysqli_query($db, "SHOW TABLES LIKE '$table'");
    return $result && mysqli_num_rows($result) > 0;
}

function createTablesFromSql()
{
    global $db;
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        return false;
    }

    $sql = file_get_contents($sqlFile);
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            mysqli_query($db, $statement . ';');
        }
    }
    return true;
}

if (!tableExists('register')) {
    createTablesFromSql();
}

function old($key)
{
    return $_POST[$key] ?? '';
}

function isSelected($value, $current)
{
    return $value === $current ? 'selected' : '';
}

function columnExists($table, $column)
{
    global $db;
    $table = mysqli_real_escape_string($db, $table);
    $column = mysqli_real_escape_string($db, $column);
    $result = mysqli_query($db, "SHOW COLUMNS FROM $table LIKE '$column'");
    return $result && mysqli_num_rows($result) > 0;
}

// Ensure karyawan table has all required columns
if (tableExists('karyawan')) {
    if (!columnExists('karyawan', 'jabatan')) {
        mysqli_query($db, "ALTER TABLE karyawan ADD COLUMN jabatan VARCHAR(255)");
    }
    if (!columnExists('karyawan', 'hp')) {
        mysqli_query($db, "ALTER TABLE karyawan ADD COLUMN hp VARCHAR(20)");
    }
}

function insertData($table, $data)
{
    global $db;

    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_map(function ($val) use ($db) {
        return mysqli_real_escape_string($db, $val);
    }, array_values($data))) . "'";

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    return mysqli_query($db, $sql);
}

function getAllData($table)
{
    global $db;
    $table = mysqli_real_escape_string($db, $table);
    $result = mysqli_query($db, "SELECT * FROM $table");

    if (!$result) {
        error_log("Database Error in getAllData: " . mysqli_error($db));
        return [];
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function getAll($table)
{
    return getAllData($table);
}

function getDataById($table, $id)
{
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $result = mysqli_query($db, "SELECT * FROM $table WHERE id = $id");
    return mysqli_fetch_assoc($result) ?? null;
}

function updateData($table, $id, $data)
{
    global $db;
    $id = mysqli_real_escape_string($db, $id);

    $set = [];
    foreach ($data as $key => $val) {
        $key = mysqli_real_escape_string($db, $key);
        $val = mysqli_real_escape_string($db, $val);
        $set[] = "$key = '$val'";
    }

    $sql = "UPDATE $table SET " . implode(", ", $set) . " WHERE id = $id";
    return mysqli_query($db, $sql);
}

function deleteData($table, $id)
{
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $sql = "DELETE FROM $table WHERE id = $id";
    return mysqli_query($db, $sql);
}

function getUserByEmailOrName($username)
{
    global $db;
    $username = mysqli_real_escape_string($db, $username);
    $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$username' OR nama = '$username'");
    return mysqli_fetch_assoc($result) ?? null;
}

function registerUser($data)
{
    global $db;

    if (!tableExists('register')) {
        createTablesFromSql();
    }

    $email = mysqli_real_escape_string($db, $data['email']);
    $nama = mysqli_real_escape_string($db, $data['nama']);
    $jenis_kelamin = mysqli_real_escape_string($db, $data['jenis_kelamin']);
    $no_hp = mysqli_real_escape_string($db, $data['no_hp']);
    $alamat = mysqli_real_escape_string($db, $data['alamat']);
    $password = mysqli_real_escape_string($db, $data['password']);
    $role = mysqli_real_escape_string($db, $data['role']);

    $sql = "INSERT INTO register (email, nama, jenis_kelamin, no_hp, alamat, password, role) 
            VALUES ('$email', '$nama', '$jenis_kelamin', '$no_hp', '$alamat', '$password', '$role')";
    return mysqli_query($db, $sql);
}

function checkEmailExists($email)
{
    global $db;

    if (!tableExists('register')) {
        createTablesFromSql();
    }

    $email = mysqli_real_escape_string($db, $email);
    $result = mysqli_query($db, "SELECT * FROM register WHERE email = '$email'");
    return mysqli_num_rows($result) > 0;
}

function getUserForLogin($username)
{
    global $db;

    if (!tableExists('register')) {
        return null;
    }

    $username = mysqli_real_escape_string($db, $username);
    $result = mysqli_query($db, "SELECT * FROM register WHERE email = '$username' OR nama = '$username'");
    return mysqli_fetch_assoc($result) ?? null;
}

function insertLoginHistory($user_id, $email, $nama, $role)
{
    global $db;
    $user_id = mysqli_real_escape_string($db, $user_id);
    $email = mysqli_real_escape_string($db, $email);
    $nama = mysqli_real_escape_string($db, $nama);
    $role = mysqli_real_escape_string($db, $role);

    $sql = "INSERT INTO login (user_id, email, nama, role) VALUES ('$user_id', '$email', '$nama', '$role')";
    return mysqli_query($db, $sql);
}
