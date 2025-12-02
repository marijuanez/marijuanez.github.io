/**
 * Form Handler - Detección automática de entorno
 * Detecta si está en local (MAMP) o producción (Firebase)
 * y ajusta el endpoint del formulario automáticamente
 */
(function() {
  'use strict';
  
  // Detectar entorno
  function isLocalEnvironment() {
    const hostname = window.location.hostname;
    return hostname === 'localhost' || 
           hostname === '127.0.0.1' || 
           hostname.includes('192.168.') ||
           hostname.includes('.local');
  }
  
  // Configurar el action del formulario según el entorno
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.php-email-form');
    
    if (form) {
      const endpoint = isLocalEnvironment() 
        ? 'forms/contact.php'  // Local MAMP
        : '/api/contact';       // Producción Firebase
      
      form.setAttribute('action', endpoint);
      
      console.log('Form endpoint configured:', endpoint);
      console.log('Environment:', isLocalEnvironment() ? 'LOCAL' : 'PRODUCTION');
    }
  });
})();
