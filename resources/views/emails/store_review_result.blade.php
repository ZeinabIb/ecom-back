<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Review Results</title>
    <style>
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Store Review Results!</h2>

    <p>Hello,</p>
    
    @if ($status == 'approved')
        <p>Congratulations! Your store "{{ $name }}" has been approved.</p>
    @elseif ($status == 'rejected')
        <p>We regret to inform you that your store "{{ $name }}" has been rejected.</p>
    @endif
    <p>Thank you!</p>
</body>
</html>
