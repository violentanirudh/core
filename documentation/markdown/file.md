# File Management Functions Documentation

## Upload Functions

### uploadFile($file, $options = [])
Handles file uploads with validation and configuration options.

**Parameters:**
- `$file`: Array from $_FILES
- `$options`: Configuration array with keys:
  - `path`: Upload directory (default: 'uploads')
  - `maxSize`: Maximum file size in bytes (default: 5MB)
  - `allowed`: Array of allowed extensions
  - `filename`: Custom filename (default: null)

```php
// Basic usage
$result = uploadFile($_FILES['document']);

// Custom configuration
$result = uploadFile($_FILES['image'], [
    'path' => 'uploads/images',
    'maxSize' => 10 * 1024 * 1024,
    'allowed' => ['jpg', 'png']
]);
```

## File Operations

### delete_file($filepath)
Deletes a file from the filesystem.

**Parameters:**
- `$filepath`: Path to the file

```php
$result = delete_file('uploads/document.pdf');
```

### get_file_info($filepath)
Retrieves detailed information about a file.

**Parameters:**
- `$filepath`: Path to the file

```php
$info = get_file_info('uploads/image.jpg');
// Returns: name, size, type, extension, modified, created dates
```

### move_file($source, $destination)
Moves a file to a new location.

**Parameters:**
- `$source`: Original file path
- `$destination`: New file path

```php
$result = move_file('uploads/old.pdf', 'archive/new.pdf');
```

### copy_file($source, $destination)
Creates a copy of a file in a new location.

**Parameters:**
- `$source`: Original file path
- `$destination`: New file path

```php
$result = copy_file('template.doc', 'uploads/new.doc');
```

## Directory Operations

### get_directory_contents($path, $recursive = false)
Lists all files in a directory.

**Parameters:**
- `$path`: Directory path
- `$recursive`: Boolean to include subdirectories

```php
$files = get_directory_contents('uploads', true);
```

### create_directory($path)
Creates a new directory.

**Parameters:**
- `$path`: Directory path to create

```php
$result = create_directory('uploads/images/2024');
```

### delete_directory($path)
Removes a directory and all its contents.

**Parameters:**
- `$path`: Directory path to delete

```php
$result = delete_directory('uploads/temp');
```

## Utility Functions

### sanitize_filename($filename)
Cleans filename by removing special characters.

**Parameters:**
- `$filename`: Original filename

```php
$clean = sanitize_filename('My File!@#.pdf');
// Returns: my-file.pdf
```

## Return Values
All functions return a standardized response array:
```php
[
    'status' => bool,    // Operation success
    'message' => string, // Status message
    'data' => mixed      // Additional data (when applicable)
]
```

## Error Handling
```php
$result = uploadFile($_FILES['document']);
if (!$result['status']) {
    redirect('upload-form', $result['message'], 'error');
}
```

## Integration Example
```php
function handle_document_upload() {
    $result = uploadFile($_FILES['document'], [
        'path' => 'uploads/documents',
        'allowed' => ['pdf', 'doc']
    ]);
    
    if (!$result['status']) {
        return redirect('upload', $result['message'], 'error');
    }
    
    return redirect('success', 'Document uploaded successfully', 'success');
}
```