<?php

function questionType($type, $slideIndex, $questionId = null) {
    // Define question types and their respective display logic
    global $question; // Ensure the $question object is accessible

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
        "text" => [
            '<input type="text" class="form-control" id="text-question-' . $slideIndex . '-' . $questionId . '" placeholder="Type your answer here">'
        ]
    ];


    if ($type === "dropdown" && $questionId !== null) {
        $choices = $question->getQuestionChoices($questionId); // Use the method from the $question object

        $output = '<select class="form-select" id="dropdown-question-' . $slideIndex . '-' . $questionId . '" name="response_' . $questionId . '">';
        $output .= '<option value="" selected>Choose an option</option>';

        foreach ($choices as $choice) {
            $output .= '<option value="' . htmlspecialchars($choice) . '">' . htmlspecialchars($choice) . '</option>';
        }

        $output .= '</select>';
        return $output;
    }

    // Check if the type exists in the defined question types
    if (array_key_exists($type, $types)) {
        $output = "";
        $index = 1; // to uniquely identify each radio button

        foreach ($types[$type] as $icon) {
            // Add slideIndex and questionId to make IDs and names unique
            $inputId = "icon{$slideIndex}_{$index}";
            $inputName = "response_{$questionId}"; // Correctly add question ID
            
            // Determine the label class based on the type
            $labelClass = ($type === "text") ? '' : 'rating-face-box-sm px-3';
            
            // Generate the output
            $output .= "
            <input type='radio' 
                id='{$inputId}' 
                name='{$inputName}' 
                class='d-none' 
                value='{$index}'>
            <label for='{$inputId}' class='{$labelClass}'>
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
