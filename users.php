<?php

class Users {

    /**
     * Get users.
     *
     * @param null $username
     */
    function getUsers ($username = null) {
        global $db_handle;
        $response = array();

        if (is_null($username)) {
            $stmt = $db_handle->prepare("
                SELECT *
                FROM users
                ORDER BY id DESC
            ");
        } else {
            $stmt = $db_handle->prepare("
                SELECT *
                FROM users
                WHERE username = :uname
            ");
            $stmt->bindParam('uname', $username);
        }

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response[] = $row;
        }

        header('Content-Type: application/json');

        echo json_encode($response);
    }

    /**
     * Save User.
     *
     * @param $data
     */
    function saveUser ($data) {
        global $db_handle;
        // TODO (abrewer) NEED TO ADD username...
        $stmt = $db_handle->prepare("
          INSERT INTO users (first_name, last_name, date_of_birth)
          VALUES (:fname, :lname, CAST(:dob AS DATE))
        ");

        $user = array(
            'fname' => $data->first_name,
            'lname' => $data->last_name,
            'dob' => $data->date_of_birth
        );

        var_dump($user);

        $result = $stmt->execute($user);

        echo $result;

        header('Content-Type: application/json');
        // Respond success / error messages
    }

    /**
     * Update User.
     *
     * @param $data
     */
    function updateUser ($data) {
        global $db_handle;
        $stmt = $db_handle->prepare("
      UPDATE users
      SET first_name=:fname,
          last_name=:lname,
          date_of_birth=CAST(:dob AS DATE)
      WHERE username=:uname
    ");

        echo $result = $stmt->execute($data);

        header('Content-Type: application/json');
        // Respond success / error messages
    }

    /**
     * Delete user.
     *
     * @param $data
     */
    function deleteUser ($data) {
        global $db_handle;
        $stmt = $db_handle->prepare("
      DELETE FROM users
      WHERE username=:uname
    ");

        echo $result = $stmt->execute($data);

        header('Content-Type: application/json');
        // Respond success / error messages
    }

}
