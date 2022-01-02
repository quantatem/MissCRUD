<?php

class Query
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function CreateTable($tablename)
    {
        $this->tablename = $tablename; //longblob как временное решение для кирилических символов в БД

        $statement = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS {$this->tablename}
        (id INTEGER NOT NULL AUTO_INCREMENT,
        firstname LONGBLOB NOT NULL,
        lastname LONGBLOB NOT NULL,
        email VARCHAR(255) NOT NULL,
        gender VARCHAR(32) NOT NULL, 
        password VARCHAR(128) NOT NULL,
        PRIMARY KEY(id)
        );");
        
        $statement->execute();

        return true;
    }

    public function CreateSecondTable($tablename)
    {
        $this->tablename2 = $tablename; // eww

        $statement = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS {$this->tablename2}
        (id INTEGER NOT NULL AUTO_INCREMENT,
        fullname LONGBLOB NOT NULL,
        email VARCHAR(255) NOT NULL,
        gender VARCHAR(32) NOT NULL,
        thread VARCHAR(32),
        maintext LONGBLOB,
        fileurl TEXT,
        PRIMARY KEY(id)
        );");
        
        $statement->execute();

        return true;
    }

    public function AddUser($values)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->tablename} (firstname, lastname, email, gender, password) 
             VALUES (:firstname, :lastname, :email, :gender, :password)"
             );
        foreach ($values as $key=>&$value) :
            $statement->bindParam(':' . $key, $value);
        endforeach;
        $statement->execute();
    }

    public function CheckUserRequirements($values) // checks for $values['email'], $values['firstname'], etc
    {

        // if (!isset($values['email'])) { return false; }
        // // validation: if the form values aren't empty
        // if (!is_array($values)) { return false; }

        if (is_array($values) && array_search(null, $values)) {
            return false;
        }
        
        // validation: if email is in fact email
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // if (isset($values['firstname']) && isset($values['lastname'])) {
        //     if (!preg_match("/^[a-zA-Z-' ]*$/", $values['firstname']) or !preg_match("/^[a-zA-Z-' ]*$/", $values['lastname'])) {
        //         return false;
        //     }
        // }

        if (!isset($values['gender'])) {
            return false;
        } else {
            if (!$values['gender'] == 'male' or !$values['gender'] == 'female') {
                return false;
            }
        }

        // check if email exists in database
        $stmt1 = $this->pdo->prepare("SELECT 1 FROM {$this->tablename} WHERE email=?"); // if same email is found then return false
        $stmt1->execute([$values['email']]); 
        if (!$stmt1->fetchColumn()) {
            return true;
        } else {
            return false;
        }
    }

    public function userExists($values)
    {
        $stmt1 = $this->pdo->prepare("SELECT * FROM {$this->tablename} WHERE email=?"); // if same email is found then return false
        $stmt1->execute([$values['email']]); 

        return password_verify($values['password'], $stmt1->fetch()['password']); //problem with arrays? 
    }

    public function userFind($email)
    {
        $stmt2 = $this->pdo->prepare("SELECT firstname, lastname, gender FROM {$this->tablename} WHERE email=?"); // if this email has been found
        $stmt2->execute([$email]); 
        $data = $stmt2->fetch();
        if ($data) {
            return [
                'fullname' => $data['firstname'] . ' ' . $data['lastname'],
                'gender' => $data['gender'],
                'email' => $email
            ];
        } else {
            return false;
        }
    }

    public function selectAll($table)
	{
		$statement = $this->pdo->prepare("select * from {$table}");

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_CLASS);
	}

    public function postMessage($data)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO {$this->tablename2} (fullname, email, gender, thread, maintext, fileurl) 
             VALUES (:fullname, :email, :gender, :thread, :maintext, :fileurl)"
             );
        foreach ($data as $key=>&$value) :
            $statement->bindParam(':' . $key, $value);
        endforeach;
        $statement->execute();
    }

    public function messageExists($maintext)
    {
        $stmt1 = $this->pdo->prepare("SELECT 1 FROM {$this->tablename2} WHERE maintext=?"); // if same email is found then return false
        $stmt1->execute([$maintext]); 

        return $stmt1->fetch();
    }

    public function fileExists($fileurl)
    {
        $stmt1 = $this->pdo->prepare("SELECT 1 FROM {$this->tablename2} WHERE fileurl=?"); // if file exists
        $stmt1->execute([$fileurl]); 

        return $stmt1->fetch();
    }

}