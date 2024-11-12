<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800">Welcome Back</h2>
        <p class="text-center text-gray-600 mb-6">Please sign in to continue</p>

        <form id="loginForm" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" required
                    class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div>
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                    Sign In
                </button>
            </div>

            <div id="errorMessage" class="text-red-600 text-center mt-4 hidden"></div>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Don’t have an account?
            <a href="register.php" class="text-blue-500 hover:text-blue-600 font-semibold">Sign Up</a>
        </p>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const email = $('#email').val();
                const password = $('#password').val();
                const apiUrl = "https://cinnamon.go.digitable.io/avidi/api/avidi/v1/custom-login";


                $('button[type="submit"]').prop('disabled', true);
                $('#errorMessage').addClass('hidden').text('');

                $.ajax({
                    type: 'POST',
                    url: apiUrl,
                    data: {
                        email: email,
                        password: password
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status === true && response.token.plainTextToken) {

                            $.ajax({
                                type: 'POST',
                                url: '/set_session.php',
                                data: {
                                    token: response.token.plainTextToken
                                },
                                success: function() {
                                    window.location.href = 'flights.php';

                                },
                                error: function() {
                                    $('#errorMessage').text('Failed to set session. Please try again.').removeClass('hidden');
                                }
                            });
                        } else {
                            $('#errorMessage').text(response.message || 'Login failed. Please try again.').removeClass('hidden');
                        }
                    },
                    error: function() {
                        $('#errorMessage').text('An error occurred while processing your request.').removeClass('hidden');
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        });
    </script>

</body>

</html>










