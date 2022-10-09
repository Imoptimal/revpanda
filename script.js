jQuery(document).ready(function($) {
    var databaseData = plugin_data.database_data;
    var ajaxUrl = plugin_data.ajax_url;
    var ajaxNonce = plugin_data.wp_revpanda_nonce;
    var inputA = document.querySelector('#input_a');
    var inputB = document.querySelector('#input_b');
    var inputC = document.querySelector('#input_c');
    var submitButton = document.querySelector('#revpanda-submit');
    var firstButton = document.querySelector('.buttons .first');
    var secondButton = document.querySelector('.buttons .second');
    var thirdButton = document.querySelector('.buttons .third');
    var fourthButton = document.querySelector('.buttons .fourth');
    var fifthButton = document.querySelector('.buttons .fifth');
    var resultDisplay = document.querySelector('.display-results #results');

    // On sumbit button click - store data to database
    submitButton.addEventListener('click', function() {
        var valueA = inputA.value;
        var valueB = inputB.value;
        var valueC = inputC.value;
        var inputValues = [];
        inputValues[0] = valueA;
        inputValues[1] = valueB;
        inputValues[2] = valueC;
        $.ajax({
            type: "POST",
            url: ajaxUrl,
            data: { // Data object
                'action': 'insert_into_database',
                'wp_revpanda_nonce': ajaxNonce,
                'wp_revpanda_data': inputValues
            },
            success: function() {
                console.log('Ajax POST successful!');
            },
            error: function(errormessage) {
                console.log('Ajax POST failed!');
            }
        });
        window.location.reload();
    });

    // Display data
    firstButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var justA = '';
        databaseData.forEach(function(item) {
            var html = '<div>' + item.a_database_values + '</div>';
            justA += html;
        });
        resultDisplay.innerHTML = '<h1>Results:</h1>' + justA;
    });

    secondButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var ABC = '';
        databaseData.forEach(function(item) {
            var html = '<div>' + item.a_database_values + '</div>';
            justA += html;
        });
        resultDisplay.innerHTML = '<h1>Results:</h1>' + justA;
    });
});