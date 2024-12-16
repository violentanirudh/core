<?php

function uploadFile($file, $options = []) {
    $defaults = [
        'path' => 'uploads',
        'maxSize' => 5 * 1024 * 1024, // 5MB
        'allowed' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        'filename' => null
    ];
    
    $options = array_merge($defaults, $options);

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return response(false, 'Upload failed');
    }

    if ($file['size'] > $options['maxSize']) {
        return response(false, 'File too large');
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $options['allowed'])) {
        return response(false, 'File type not allowed');
    }

    if (!file_exists($options['path'])) {
        mkdir($options['path'], 0777, true);
    }

    $filename = $options['filename'] === null ? uniqid() . '.' . $extension : $options['filename'] . '.' . $extension;
    $filepath = $options['path'] . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return response(true, 'File uploaded successfully', [
            'name' => $filename,
            'path' => $filepath
        ]);
    }

    return response(false, 'Failed to move uploaded file');
}

// Core file helper functions
function delete_file($filepath) {
    if (!file_exists($filepath)) {
        return response(false, 'File not found');
    }
    
    if (unlink($filepath)) {
        return response(true, 'File deleted successfully');
    }
    return response(false, 'Failed to delete file');
}

function get_file_info($filepath) {
    if (!file_exists($filepath)) {
        return response(false, 'File not found');
    }
    
    $info = [
        'name' => basename($filepath),
        'size' => filesize($filepath),
        'type' => mime_content_type($filepath),
        'extension' => pathinfo($filepath, PATHINFO_EXTENSION),
        'modified' => filemtime($filepath),
        'created' => filectime($filepath)
    ];
    
    return response(true, 'File info retrieved', $info);
}

function move_file($source, $destination) {
    if (!file_exists($source)) {
        return response(false, 'Source file not found');
    }
    
    $dir = dirname($destination);
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    if (rename($source, $destination)) {
        return response(true, 'File moved successfully');
    }
    return response(false, 'Failed to move file');
}

function copy_file($source, $destination) {
    if (!file_exists($source)) {
        return response(false, 'Source file not found');
    }
    
    $dir = dirname($destination);
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    if (copy($source, $destination)) {
        return response(true, 'File copied successfully');
    }
    return response(false, 'Failed to copy file');
}

function get_directory_contents($path, $recursive = false) {
    if (!file_exists($path)) {
        return response(false, 'Directory not found');
    }
    
    $files = [];
    $iterator = $recursive 
        ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path))
        : new DirectoryIterator($path);
    
    foreach ($iterator as $file) {
        if ($file->isDot()) continue;
        
        $files[] = [
            'name' => $file->getFilename(),
            'path' => $file->getPathname(),
            'type' => $file->getType(),
            'size' => $file->getSize()
        ];
    }
    
    return response(true, 'Directory contents retrieved', $files);
}

function create_directory($path) {
    if (file_exists($path)) {
        return response(false, 'Directory already exists');
    }
    
    if (mkdir($path, 0777, true)) {
        return response(true, 'Directory created successfully');
    }
    return response(false, 'Failed to create directory');
}

function delete_directory($path) {
    if (!file_exists($path)) {
        return response(false, 'Directory not found');
    }
    
    $files = array_diff(scandir($path), ['.', '..']);
    foreach ($files as $file) {
        $filepath = $path . '/' . $file;
        is_dir($filepath) ? delete_directory($filepath) : unlink($filepath);
    }
    
    if (rmdir($path)) {
        return response(true, 'Directory deleted successfully');
    }
    return response(false, 'Failed to delete directory');
}

function sanitize_filename($filename) {
    // Remove special characters
    $filename = preg_replace('/[^a-zA-Z0-9\-\_\.]/', '', $filename);
    // Remove multiple dots
    $filename = preg_replace('/\.+/', '.', $filename);
    // Convert to lowercase
    return strtolower($filename);
}
