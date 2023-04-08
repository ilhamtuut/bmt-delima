/**
 *  Pages Authentication
 */

'use strict';
const formAuthentication = document.querySelector('#formAuthentication');

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // Form validation for Add new record
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          name: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nama lengkap'
              }
            }
          },
          username: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan username'
              },
              stringLength: {
                min: 6,
                message: 'Username harus lebih dari 6 karakter'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan email'
              },
              emailAddress: {
                message: 'Masukkan alamat email yang valid'
              }
            }
          },
          'email-username': {
            validators: {
              notEmpty: {
                message: 'Please enter email / username'
              },
              stringLength: {
                min: 6,
                message: 'Username harus lebih dari 6 karakter'
              }
            }
          },
          phone_number: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan no telp'
              }
            }
          },
          address: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan alamat'
              }
            }
          },
          ktp: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan no ktp'
              }
            }
          },
          foto_ktp: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan foto ktp'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan password'
              },
              stringLength: {
                min: 8,
                message: 'Password harus lebih dari 8 karakter'
              }
            }
          },
          'password_confirmation': {
            validators: {
              notEmpty: {
                message: 'Mohon masukan confirm password'
              },
              identical: {
                compare: function () {
                  return formAuthentication.querySelector('[name="password"]').value;
                },
                message: 'Kata sandi dan konfirmasinya tidak sama'
              },
              stringLength: {
                min: 8,
                message: 'Password harus lebih dari 8 karakter'
              }
            }
          },
          terms: {
            validators: {
              notEmpty: {
                message: 'Please agree terms & conditions'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-3'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
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

    //  Two Steps Verification
    const numeralMask = document.querySelectorAll('.numeral-mask');

    // Verification masking
    if (numeralMask.length) {
      numeralMask.forEach(e => {
        new Cleave(e, {
          delimiter: '',
          numeral: true
        });
      });
    }
  })();
});
