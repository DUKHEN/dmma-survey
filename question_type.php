<?php

function questionType($type, $slideIndex, $questionId = null) {
    // Define question types and their respective display logic
    $types = [
        "rating" => [
            '<i class="bi bi-star-fill"></i>',
            '<i class="bi bi-star-fill"></i>',
            '<i class="bi bi-star-fill"></i>',
            '<i class="bi bi-star-fill"></i>',
            '<i class="bi bi-star-fill"></i>'   
        ],
        "thumbs" => [
            '<i class="bi bi-hand-thumbs-up-fill"></i>',
            '<i class="bi bi-hand-thumbs-down-fill"></i>'
        ],
        "emotion" => [
            '<i class="bi bi-emoji-laughing-fill"></i>',  
            '<i class="bi bi-emoji-smile-fill"></i>',    
            '<i class="bi bi-emoji-neutral-fill"></i>',  
            '<i class="bi bi-emoji-frown-fill"></i>',    
            '<i class="bi bi-emoji-angry-fill"></i>' 
        ],
        "text" => []
    ];

    // Check if the type exists in the defined question types
    if (array_key_exists($type, $types)) {
        $output = "";
        $index = 1; // to uniquely identify each radio button

        foreach ($types[$type] as $icon) {
            // Add slideIndex and questionId to make IDs and names unique
            $inputId = "icon{$slideIndex}_{$index}";
            $inputName = "response_{$questionId}"; // Correctly add question ID
            $output .= "
            <input type='radio' 
                id='{$inputId}' 
                name='{$inputName}' 
                class='d-none' 
                value='{$index}'>
            <label for='{$inputId}' class='rating-face-box-sm px-3'>
                {$icon}
            </label>";
            $index++;
        }

        return $output; // Return the generated HTML
    } else {
        // Handle unsupported types
        return "<p>Unsupported question type: {$type}</p>";
    }
}

?>
