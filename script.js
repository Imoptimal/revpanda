jQuery(document).ready(function($) {
    var ajaxUrl = plugin_data.ajax_url;
    var postNonce = plugin_data.post_revpanda_nonce;
    var getNonce = plugin_data.get_revpanda_nonce;
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
    var ajaxResult;
    var LsItem = 'revpandaData';
    var noData = 'Data Empty!';
    var revpandaLs = JSON.parse(localStorage.getItem(LsItem));
    // Initial localStorage set
    if (revpandaLs === null) {
        localStorage.setItem(LsItem, JSON.stringify(noData));
    }
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
                'post_revpanda_nonce': postNonce,
                'wp_revpanda_data': inputValues
            },
            success: function() {
                console.log('Ajax POST successful!');
            },
            error: function(errormessage) {
                console.log('Ajax POST failed!');
            }
        }).then(
            function() {
                $.ajax({
                    type: "GET",
                    url: ajaxUrl,
                    dataType: 'json',
                    data: { // Data object
                        'action': 'get_database_data',
                        'get_revpanda_nonce': getNonce
                    },
                    success: function(res) {
                        console.log('Ajax GET successful!');
                    },
                    error: function(errormessage) {
                        console.log('Ajax GET failed!');
                    }
                }).done(function(res) {
                    ajaxResult = res.data;
                })
            });
    });

    // Display data - first button click
    function firstDisplay(allData, filteredData) {
        allData.forEach(function(item) {
            var html = '<span>' + item.a_database_values + '</span>';
            filteredData += html;
        });
        resultDisplay.innerHTML = '<h3>Results:</h3><h4>Table A: </h4><div>' + filteredData + '</div>';
    }
    firstButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var LsData = JSON.parse(localStorage.getItem(LsItem));
        var storedData;
        var justA = '';
        if (ajaxResult) {
            storedData = ajaxResult;
            firstDisplay(storedData, justA);
        } else if (LsData !== noData) {
            storedData = LsData;
            firstDisplay(storedData, justA);
        } else {
            storedData = noData;
            resultDisplay.innerHTML = '<div>' + noData + '</div>';
        }
        localStorage.setItem(LsItem, JSON.stringify(storedData));
    });

    // Display data - second button click
    function secondDisplay(allData, dataA, dataB, dataC) {
        allData.forEach(function(item) {
            var aSpans = '<span>' + item.a_database_values + '</span>';
            dataA += aSpans;
            var bSpans = '<span>' + item.b_database_values + '</span>';
            dataB += bSpans;
            var cSpans = '<span>' + item.c_database_values + '</span>';
            dataC += cSpans;
        });
        var ABC = '<h4>Table A: </h4><div>' + dataA + '</div><h4>Table B: </h4><div>' + dataB + '</div><h4>Table C: </h4><div>' + dataC + '</div>';
        resultDisplay.innerHTML = '<h3>Results:</h3>' + ABC;
    }
    secondButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var LsData = JSON.parse(localStorage.getItem(LsItem));
        var storedData;
        var justA = '';
        var justB = '';
        var justC = '';
        if (ajaxResult) {
            storedData = ajaxResult;
            secondDisplay(storedData, justA, justB, justC);
        } else if (LsData !== noData) {
            storedData = LsData;
            secondDisplay(storedData, justA, justB, justC);
        } else {
            storedData = noData;
            resultDisplay.innerHTML = '<div>' + noData + '</div>';
        }
        localStorage.setItem(LsItem, JSON.stringify(storedData));
    });

    // Display data - third button click
    function thirdDisplay(allData, dataC, dataB) {
        allData.forEach(function(item) {
            var cSpans = '<span>' + item.c_database_values + '</span>';
            dataC += cSpans;
            var bSpans = '<span>' + item.b_database_values + '</span>';
            dataB += bSpans;
        });
        var CB = '<h4>Table C: </h4><div>' + dataC + '</div><h4>Table B: </h4><div>' + dataB + '</div>';
        resultDisplay.innerHTML = '<h3>Results:</h3>' + CB;
    }
    thirdButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var LsData = JSON.parse(localStorage.getItem(LsItem));
        var storedData;
        var justC = '';
        var justB = '';
        if (ajaxResult) {
            storedData = ajaxResult;
            thirdDisplay(storedData, justC, justB);
        } else if (LsData !== noData) {
            storedData = LsData;
            thirdDisplay(storedData, justC, justB);
        } else {
            storedData = noData;
            resultDisplay.innerHTML = '<div>' + noData + '</div>';
        }
        localStorage.setItem(LsItem, JSON.stringify(storedData));
    });

    // Display data - fourth button click
    function fourthDisplay(allData, dataB) {
        allData.forEach(function(item) {
            var bSpans = '<span>' + item.b_database_values + '</span>';
            dataB += bSpans;
        });
        var B = '<h4>Table B: </h4><div>' + dataB + '</div>';
        resultDisplay.innerHTML = '<h3>Results:</h3>' + B;
    }
    fourthButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var LsData = JSON.parse(localStorage.getItem(LsItem));
        var storedData;
        var justB = '';
        if (ajaxResult) {
            storedData = ajaxResult;
            fourthDisplay(storedData, justB);
        } else if (LsData !== noData) {
            storedData = LsData;
            fourthDisplay(storedData, justB);
        } else {
            storedData = noData;
            resultDisplay.innerHTML = '<div>' + noData + '</div>';
        }
        localStorage.setItem(LsItem, JSON.stringify(storedData));
    });

    // Display data - fifth button click
    function fifthDisplay(allData, dataB) {
        var reverseData = allData.slice().reverse();
        reverseData.forEach(function(item) {
            var bSpans = '<span>' + item.b_database_values + '</span>';
            dataB += bSpans;
        });
        var B = '<h4>Table B: </h4><div>' + dataB + '</div>';
        resultDisplay.innerHTML = '<h3>Results:</h3>' + B;
    }
    fifthButton.addEventListener('click', function() {
        resultDisplay.innerHTML = '';
        var LsData = JSON.parse(localStorage.getItem(LsItem));
        var storedData;
        var justB = '';
        if (ajaxResult) {
            storedData = ajaxResult;
            fifthDisplay(storedData, justB);
        } else if (LsData !== noData) {
            storedData = LsData;
            fifthDisplay(storedData, justB);
        } else {
            storedData = noData;
            resultDisplay.innerHTML = '<div>' + noData + '</div>';
        }
        localStorage.setItem(LsItem, JSON.stringify(storedData));
    });
});