<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    
    <h2>Welcome, {{ $student->first_name }}!</h2>

    <p>You have been successfully enrolled. </p>

    <p>Here are your login credentials:
    
    <div style="background-color: #f4f4f4; padding: 15px; border-radius: 5px; border-left: 5px solid #800000; width: fit-content;">
        <p><strong>Student Number:</strong> {{ $student->student_number }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
    </div>

    <p>Please login immediately and change your password if necessary.</p>
    
    <p>Thank you!</p>
</body>
</html>