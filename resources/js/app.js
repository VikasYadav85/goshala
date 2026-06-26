import './bootstrap';
import QRCode from 'qrcode';

// Render any UPI QR codes on the page. An element with [data-upi-qr] whose
// value is a upi:// intent string is turned into a scannable QR canvas.
function renderUpiQrCodes() {
    document.querySelectorAll('[data-upi-qr]').forEach((el) => {
        const data = el.getAttribute('data-upi-qr');
        if (!data || el.dataset.rendered) return;
        el.dataset.rendered = '1';
        const canvas = document.createElement('canvas');
        el.appendChild(canvas);
        QRCode.toCanvas(canvas, data, { width: 220, margin: 1 }, (err) => {
            if (err) console.error('UPI QR render failed', err);
        });
    });
}

document.addEventListener('DOMContentLoaded', renderUpiQrCodes);
