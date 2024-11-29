<?php
if (isset($_POST['hidden_field'])) {
    $error = '';
    $total_line = '';
    session_start();

    if (!empty($_FILES['file']['name'])) {
        $allowed_extension = ['csv'];
        $file_array = explode(".", $_FILES["file"]["name"]);
        $extension = strtolower(end($file_array));

        if (in_array($extension, $allowed_extension)) {
            $new_file_name = uniqid() . '.' . $extension;
            $_SESSION['csv_file_name'] = $new_file_name;
            $upload_path = 'file/' . $new_file_name;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
                $file_content = file($upload_path, FILE_SKIP_EMPTY_LINES);
                $total_line = count($file_content);
            } else {
                $error = 'File upload failed. Please try again.';
            }
        } else {
            $error = 'Only CSV file format is allowed';
        }
    } else {
        $error = 'Please select a file';
    }

    if ($error != '') {
        $output = ['error' => $error];
    } else {
        $output = [
            'success' => true,
            'total_line' => ($total_line - 1)
        ];
    }

    echo json_encode($output);
}
