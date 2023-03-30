<!DOCTYPE html>
<html>
<head>
  <title>Form using Table with Bootstrap and CSS</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    table {
      width: 100%;
      max-width: 600px;
      margin: auto;
    }

    label {
      font-weight: bold;
      display: inline-block;
      margin-bottom: 0.5rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    textarea {
      width: 100%;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      color: #495057;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    textarea:focus {
      outline: none;
      border-color: #80bdff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    input[type="submit"] {
      display: block;
      margin: 1rem auto;
      padding: 0.5rem 1rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #fff;
      background-color: #007bff;
      border: none;
      border-radius: 0.25rem;
      transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    input[type="submit"]:hover {
      background-color: #0069d9;
    }
  </style>
</head>
<body>
  <div class="container">
    <form>
      <table>
        <tr>
          <td><label for="name">Name:</label></td>
          <td><input type="text" id="name" name="name"></td>
        </tr>
        <tr>
          <td><label for="email">Email:</label></td>
          <td><input type="email" id="email" name="email"></td>
        </tr>
        <tr>
          <td><label for="password">Password:</label></td>
          <td><input type="password" id="password" name="password"></td>
        </tr>
        <tr>
          <td><label for="message">Message:</label></td>
          <td colspan="3"><textarea id="message" name="message"></textarea></td>
        </tr>
        <tr>
          <td colspan="4"><input type="submit" value="Submit"></td>
        </tr>
      </table>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/esm/popper-base" integrity="sha384-QLlTtZ1vGxKxNRJhgs"></script>
</body>
</html>
