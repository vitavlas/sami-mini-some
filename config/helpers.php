<?php

/**
 * Validate forms
 */
function validateInput(array $patterns, array $data): array {
    // Error container
    $errors = [];
    
    foreach ($patterns as $field => $pattern) {
        // Define pattern fields
        $label = $pattern['label'];
        $type = $pattern['type'];
        $rule = $pattern['rule'];
        $message = $pattern['message'];

        // Form input field to be checked
        $value = $data[$field] ?? "";

        // Process field validation
        switch ($type) {
            case "regex":
                if (!preg_match($rule, $value)) {
                    $errors[] = $message;
                }
                break;
            case "filter":
                if (!filter_var($value, $rule)) {
                    $errors[] = $message;
                }
                break;
        }
    }

    return [
        "isValid" => empty($errors),
        "data" => $data,
        "errors" => $errors
    ];
}

/**
 * Sanitize form inputs
 * delete spaces at start/end of string
 * transform multispaces into the string to one space
 */
function sanitizeInput(array $data): array {
    foreach ($data as $field => $value) {
        // delete spaces at the begin and end of the string
        $data[$field] = trim($value);
        // delete excess spaces inside the string
        $data[$field] = preg_replace('/\s+/', ' ', $value);
    }

    return $data;
}