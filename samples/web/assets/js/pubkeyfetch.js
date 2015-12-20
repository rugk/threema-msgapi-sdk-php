/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015 Threema GmbH
 */

// Get elements
var inputSender = document.getElementById("SenderIdInput");
var outputSender = document.getElementById("SenderPubKey");
var inputReceiver = document.getElementById("RecieverIdInput");
var outputReceiver = document.getElementById("RecieverPubKey");
// alert("inputSender.value: "+inputSender.value);

// Add onload events
window.onload = updatePubKey(inputSender, outputSender);
window.onload = updatePubKey(inputReceiver, outputReceiver);

// Add onchange events
inputReceiver.addEventListener("change", function() {
  updatePubKey(inputReceiver, outputReceiver);
});

/**
 * Updates the public key by reading a Threema ID from an input box and showing
 * the result in another element.
 *
 * @param {Object} input - The element where the Threema ID is stored.
 * @param {string} input.value - The Threema ID
 * @param {Object} output - The element where the public should be written to
 * @param {Object} output.innerHTML
 */
function updatePubKey(input, output) {
  // create vars
  var xhttp = new XMLHttpRequest();
  var threemaid = input.value;

  // show on change
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4) {
      if (xhttp.status == 200) {
        output.innerHTML =  "public key: " + xhttp.responseText;
      } else {
        output.innerHTML = "error when fetching public key";
        console.log("Public key request for " + threemaid + " failed. Result: "
        + "(" + xhttp.status + ") " + xhttp.responseText);
      }
    }
  };

  // send request
  output.innerHTML = "Fetching public key... Please wait...";
  xhttp.open("GET", "publickey.php?threemaid=" + threemaid, true);
  xhttp.send();
}
