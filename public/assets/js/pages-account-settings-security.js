/**
 * Account Settings - Security
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formChangePass = document.querySelector('#formAccountSettings'),
      formApiKey = document.querySelector('#formAccountSettingsApiKey');

    // Form validation for Change password
    if (formChangePass) {
      const fv = FormValidation.formValidation(formChangePass, {
        fields: {
          current_password: {
            validators: {
              notEmpty: {
                message: 'Masukan password saat ini'
              },
              stringLength: {
                min: 8,
                message: 'Kata sandi harus lebih dari 8 karakter'
              }
            }
          },
          new_password: {
            validators: {
              notEmpty: {
                message: 'Masukan password baru'
              },
              stringLength: {
                min: 8,
                message: 'Kata sandi harus lebih dari 8 karakter'
              }
            }
          },
          confirm_password: {
            validators: {
              notEmpty: {
                message: 'Masukan konfirmasi password baru'
              },
              identical: {
                compare: function () {
                  return formChangePass.querySelector('[name="new_password"]').value;
                },
                message: 'Password dan konfirmasinya tidak sama'
              },
              stringLength: {
                min: 8,
                message: 'Kata sandi harus lebih dari 8 karakter'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.col-md-6'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          // Submit the form when all fields are valid
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });

      fv.on('core.form.valid', function() {
        $.blockUI({ message: null });
      });
    }

    // Form validation for API key
    if (formApiKey) {
      const fvApi = FormValidation.formValidation(formApiKey, {
        fields: {
          apiKey: {
            validators: {
              notEmpty: {
                message: 'Please enter API key name'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: ''
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          // Submit the form when all fields are valid
          // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }
  })();
});

// Select2 (jquery)
$(function () {
  var select2 = $('.select2');

  // Select2 API Key
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        dropdownParent: $this.parent()
      });
    });
  }
});
