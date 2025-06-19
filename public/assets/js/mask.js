(function ($) {
  'use strict';

  // Définition personnalisée, reste inchangée
  $.mask.definitions['~'] = '[+-]';

  // --- Application des masques par classe ---

  // Masques existants
  $('.mask-date').mask('99/99/9999');
  $('.mask-time').mask('99:99');
  $('.mask-phone').mask('(999) 999-9999'); // Téléphone US
  $('.mask-phone-ext').mask('(999) 999-9999? x99999');
  $('.mask-iphone').mask('+33 999 999 999'); // Téléphone international
  $('.mask-tin').mask('99-9999999');
  $('.mask-ccn').mask('9999 9999 9999 9999');
  $('.mask-ssn').mask('999-99-9999'); // Sécurité Sociale US

  // --- NOUVEAUX MASQUES AJOUTÉS ---
  // Masque pour Numéro de Sécurité Sociale (format français à 15 chiffres)
  $('.mask-ssn-fr').mask('9 99 99 99 999 999 99');

  // Masque pour Code Postal (format français à 5 chiffres)
  $('.mask-postal-code').mask('99999');

  // Masque pour Téléphone (format français national à 10 chiffres)
  $('.mask-phone-fr').mask('099 99 99 99');
  // --- FIN DES NOUVEAUX MASQUES ---
  
  // Autres masques existants
  $('.mask-currency').mask('999,999,999.99');
  $('.mask-product').mask('a*-999-a999', { placeholder: ' ' });
  $('.mask-eyescript').mask('~9.99 ~9.99 999');
  $('.mask-po').mask('PO: aaa-999-***');
  $('.mask-pct').mask('99%');

  // Exemples avec des options
  $('.mask-phone-no-autoclear').mask('(999) 999-9999', { autoclear: false });
  $('.mask-phone-ext-no-autoclear').mask('(999) 999-9999? x99999', { autoclear: false });
// Masque pour Numéro de Sécurité Sociale (format français à 15 chiffres)
$('.mask-ssn-fr').mask('9 99 99 99 999 999 99');

// Masque pour Code Postal (format français à 5 chiffres)
$('.mask-postal-code').mask('99999');


// --- NOUVEAUX MASQUES SUGGÉRÉS ---
// Masque pour IBAN (format français)
$('.mask-iban').mask('FR99 AAAA AAAA AAAA AAAA AAAA A99', { 'translation': { A: { pattern: /[0-9a-zA-Z]/, recursive: true } } });

// Masque pour BIC/SWIFT
$('.mask-bic').mask('AAAAAA**', { 'translation': { A: { pattern: /[0-9a-zA-Z]/, recursive: true } } });
// --- FIN DES NOUVEAUX MASQUES ---

  // --- Code général, reste inchangé ---
  // Cet événement s'applique à tous les inputs, ce qui est déjà une bonne pratique.
  $('input').blur(function () {
    // Note : vous aviez un #info, assurez-vous qu'il existe
    // $('#info').html('Unmasked value: ' + $(this).mask());
  }).dblclick(function () {
    $(this).unmask();
  });

})(jQuery);

