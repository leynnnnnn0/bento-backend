<?php

abstract class Model
{
    public abstract function table() : string;
    public abstract function attributes() : array;
    public array $errors = [];

    public function loadData(array $data) : void
    {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
        }
    }

    public function validate(): bool
    {
        $attributes = $this->attributes();
        $table = $this->table();
        foreach($attributes as $key => $errors)
        {
            // Errors is an array
            // I have to iterate on that array
            foreach ($errors as $rule) {
                $value = $this->{$key};
                $errors = $rule;

                // if the error is an array get the first value
                if(is_array($rule)) $rule = $rule[0];

                if($rule === 'required' && empty($value))
                    $this->errors[$key][] = "$key is required";
                if($rule === 'min' && strlen($value) < $errors[1])
                    $this->errors[$key][] = "$key should be at least $errors[1] characters";
                if($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    $this->errors[$key][] = "$key is not a valid email address";
                if($rule === 'match' && $value !== $this->{$key . '_confirmation'})
                    $this->errors[$key . '_confirmation'][] = "$key doesnt match";
                if($rule === 'unique')
                {
                    $table = $this->table();
                    $query = "SELECT $key FROM $table WHERE $key = '$value'";
                    $statement = Application::$app->db->query($query);
                    if($statement->rowCount() > 0)
                        $this->errors[$key][] = "$key already exists.";
                }
            }
        }
        return !empty($this->errors);
    }
}