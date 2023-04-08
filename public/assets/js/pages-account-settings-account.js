/**
 * Account Settings - Account
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAccSettings = document.querySelector('#formAccountSettings');

    // Form validation for Add new record
    if (formAccSettings) {
      const fv = FormValidation.formValidation(formAccSettings, {
        fields: {
          bank_name: {
            validators: {
              notEmpty: {
                message: 'Mohon pilih nama bank'
              }
            }
          },
          account_name: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nama akun'
              }
            }
          },
          account_number: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nomor rekening'
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

    const formCreateUser = document.querySelector('#formCreateUser');

    // Form validation for Add new record
    if (formCreateUser) {
      const user = FormValidation.formValidation(formCreateUser, {
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
          role: {
            validators: {
              notEmpty: {
                message: 'Mohon pilih role'
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
          bank_name: {
            validators: {
              notEmpty: {
                message: 'Mohon pilih nama bank'
              }
            }
          },
          account_name: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nama akun'
              }
            }
          },
          account_number: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nomor rekening'
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

      user.on('core.form.valid', function() {
        $.blockUI({ message: null });
      });
    }

    const formEditUser = document.querySelector('#formEditUser');

    // Form validation for Add new record
    if (formEditUser) {
      const user = FormValidation.formValidation(formEditUser, {
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
          role: {
            validators: {
              notEmpty: {
                message: 'Mohon pilih role'
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
            
          },
          bank_name: {
            validators: {
              notEmpty: {
                message: 'Mohon pilih nama bank'
              }
            }
          },
          account_name: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nama akun'
              }
            }
          },
          account_number: {
            validators: {
              notEmpty: {
                message: 'Mohon masukan nomor rekening'
              }
            }
          },
          password: {
            validators: {
              stringLength: {
                min: 8,
                message: 'Password harus lebih dari 8 karakter'
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

      user.on('core.form.valid', function() {
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

// Select2 (jquery)
$(function () {
  var select2 = $('.select2');
  // For all Select2
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
