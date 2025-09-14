<script>
    // implementasi modal dapat klik fungsi yang di keydown
    // cash
    document.getElementById('modal-cash-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '>', // Simulate the '>' key press
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-cash-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '>', // Simulate the '>' key press
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // search
    document.getElementById('modal-search-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F11',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-search-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F11',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // void
    document.getElementById('modal-void-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F4',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-void-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F4',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // all-void
    document.getElementById('modal-all-void-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F5',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-all-void-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F5',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // return
    document.getElementById('modal-return-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F6',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-return-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F6',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // subtotal
    document.getElementById('modal-subtotal-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '?',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-subtotal-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '?',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // percent
    document.getElementById('modal-percent-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'P',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-percent-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'P',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // rupiah
    document.getElementById('modal-rupiah-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '+',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-rupiah-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '+',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // end-day
    document.getElementById('modal-end-day-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F7',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-end-day-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F7',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // hold
    document.getElementById('modal-hold-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F8',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-hold-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F8',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // list-hold
    document.getElementById('modal-list-hold-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F9',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-list-hold-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F9',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // clear
    document.getElementById('modal-clear-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '-',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-clear-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: '-',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // pembelian
    document.getElementById('modal-pembelian-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'R',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-pembelian-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'R',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // kasir
    document.getElementById('modal-kasir-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F12',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-kasir-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F12',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // transfer
    document.getElementById('modal-transfer-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'V',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-transfer-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'V',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // kembali
    document.getElementById('modal-kembali-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'Y',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-kembali-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'Y',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // kredit
    document.getElementById('modal-kredit-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'U',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-kredit-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'U',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    // signoff
    document.getElementById('modal-signoff-cell').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F2',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
    
    document.getElementById('modal-signoff-cell2').addEventListener('click', function() {
        // Create and dispatch a 'keydown' event programmatically
        const event = new KeyboardEvent('keydown', {
            key: 'F2',
            bubbles: true
        });
        document.dispatchEvent(event);
    });
</script>