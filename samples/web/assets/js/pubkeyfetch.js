/**
 * @author rugk
 * @copyright Copyright (c) 2015 rugk
 * @license MIT
 */

// Get elements
var inputSender = document.getElementById("SenderIdInput");
var outputSender = document.getElementById("SenderPubKey");
var inputReceiver = document.getElementById("RecieverIdInput");
var outputReceiver = document.getElementById("RecieverPubKey");
// alert("inputSender.value: "+inputSender.value);

// Add general events
window.onload = UpdatePubKey(inputSender, outputSender);
window.onload = UpdatePubKey(inputReceiver, outputReceiver);

// Add specific events
inputReceiver.addEventListener("change", function() {
  UpdatePubKey(inputReceiver, outputReceiver);
});

/**
 * Updates the public key by reading a Threema ID from an input box and showing
 * the result in another element.
 *
 * @param {Object} input - The element where the Threema ID is stored.
 * @param {string} input.value - The Threema ID
 * @param {Object} output - The element where the public should be written to
 * @param {Object} output.innerHTML - The HTML content where this should bewritten to
 *                 written to
 * @returns {undefined}
 */
function UpdatePubKey(input, output) {
  // vars
  var xhttp = new XMLHttpRequest();
  var threemaid = input.value;

  // show on change
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState === 4) {
      if (xhttp.status === 200) {
        output.innerHTML = "public key: " + xhttp.responseText;
      } else {
        output.innerHTML = "error when fetching public key";
        console.log("Public key request for " + threemaid + " failed. Result: "
        + "(" + xhttp.status + ") " + xhttp.responseText);
      }
    }
  };

  // send request
  output.innerHTML = "Fetching public key... Please wait...";
  xhttp.open("GET", "FetchPublicKey.php?threemaid=" + threemaid, true);
  xhttp.send();
}
