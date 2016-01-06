/**
 * @author rugk
 * @copyright Copyright (c) 2015 rugk
 * @license MIT
 */

// Add general events
window.onload = UpdateMethodState();

// Add specific events
document.getElementById("SrvMethodGet").addEventListener("change", function() {
  UpdateRequestMethod("get");
});
document.getElementById("SrvMethodPost").addEventListener("change", function() {
  UpdateRequestMethod("post");
});

document.getElementById("ButtonExternalScript").addEventListener("change", function() {
  UpdateExternalScript();
});

/**
 * Update the form submission method when clicking on a radio button.
 *
 * This update has to be run onload as most browser cache the selection and even
 * when you do not submit the form, but just reload you may end up with
 *
 * @returns {undefined}
 */
function UpdateMethodState() {
  //change method
  UpdateRequestMethod(document.querySelector("input[name='servermethod']:checked").value);
  UpdateExternalScript();
}

/**
 * Update the form submission method when clicking on a radio button.
 *
 * @param {Object} newMethod - The new method which should be used. Use
 *                             either "get" or "post".
 * @returns {undefined}
 */
function UpdateRequestMethod(newMethod) {
  //vars
  var form = document.getElementById("mainform");

  //change method
  form.method = newMethod;
}

/**
 * Update the form submission method when clicking on a radio button.
 *
 * When "use own script" is selected it automatically shows the form in a new
 * tab.
 *
 * @returns {undefined}
 */
function UpdateExternalScript() {
  //vars
  var button = document.getElementById("ButtonExternalScript");
  var form = document.getElementById("mainform");

  //change action
  if (button.checked) {
    form.action = "SendTextMessage.php";
    form.target = "_blank";
  } else {
    form.action = ".";
    form.target = "";
  }
}
