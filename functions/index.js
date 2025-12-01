const functions = require('firebase-functions');
const admin = require('firebase-admin');
const express = require('express');
const cors = require('cors');

// Initialize Firebase Admin
if (!admin.apps.length) {
  admin.initializeApp();
}
const db = admin.firestore();

const app = express();
// Enable CORS for all requests
app.use(cors({ origin: '*' }));
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Handle OPTIONS requests for CORS
app.options('*', cors({ origin: '*' }));

// Health check endpoint
app.get('/', (req, res) => {
  res.json({ status: 'ok', service: 'submitContact' });
});

// Unified contact handler for '/' and '/api/contact' (rewrite scenarios)
async function contactHandler(req, res) {
  try {
    const { name, email, subject, message } = (req.body || {});
    // Basic server-side validation
    if (!name || name.trim().length < 4) return res.status(400).json({ error: 'El nombre debe tener al menos 4 caracteres.' });
    if (!email || !/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i.test(email)) return res.status(400).json({ error: 'Email inválido.' });
    if (!subject || subject.trim().length < 4) return res.status(400).json({ error: 'Asunto inválido.' });
    if (!message || message.trim().length === 0) return res.status(400).json({ error: 'Mensaje vacío.' });

    const doc = {
      name: String(name).trim(),
      email: String(email).trim(),
      subject: String(subject).trim(),
      message: String(message).trim(),
      ip_address: req.headers['fastly-client-ip'] || req.ip || null,
      user_agent: req.headers['user-agent'] || null,
      created_at: admin.firestore.FieldValue.serverTimestamp(),
      read_status: 0
    };

    const ref = await db.collection('messages').add(doc);

    // Optional: send notification email via SendGrid
    if (sendgrid) {
      try {
        const notification = {
          to: process.env.NOTIFY_EMAIL || 'oskar.marijuan@gmail.com',
          from: process.env.FROM_EMAIL || 'noreply@portfolio.example.com',
          subject: `Nuevo mensaje: ${doc.subject}`,
          text: `Nuevo mensaje de ${doc.name} <${doc.email}>\n\n${doc.message}`
        };
        await sendgrid.send(notification);
      } catch (e) {
        console.warn('SendGrid send failed', e && e.message);
      }
    }

    return res.json({ success: true, message: 'Mensaje enviado correctamente', id: ref.id });
  } catch (err) {
    console.error('submitContact error:', err);
    return res.status(500).json({ error: 'Error al procesar el formulario: ' + (err?.message || 'unknown error') });
  }
}

// Accept both root and explicit path (in case rewrites preserve path)
app.post('/', contactHandler);
app.post('/api/contact', contactHandler);
// Generic catch-all: treat as contact to be lenient, or return 404 if desired
app.post('*', contactHandler);

// Optional SendGrid (moved after routes)
let sendgrid;
if (process.env.SENDGRID_API_KEY) {
  try {
    sendgrid = require('@sendgrid/mail');
    sendgrid.setApiKey(process.env.SENDGRID_API_KEY);
  } catch (e) {
    console.warn('SendGrid not available or failed to init');
  }
}

exports.submitContact = functions.runWith({ memory: '256MB' }).https.onRequest(app);
