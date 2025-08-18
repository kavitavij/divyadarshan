
<style>
    .payment-card {
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }
    .payment-card .card-header {
        background-color: #4a148c; /* Deep purple */
        color: white;
        text-align: center;
        font-size: 1.5rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }
    .payment-card .card-body {
        padding: 2rem;
    }
    .summary-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
    }
    .summary-box .total-amount {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
    }
    .btn-pay-now {
        background: linear-gradient(135deg, #28a745, #218838);
        border: none;
        font-size: 1.2rem;
        padding: 0.75rem;
    }
    /* --- NEW STYLES FOR PAYMENT TABS --- */
    .payment-methods-nav {
        display: flex;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 1.5rem;
    }
    .payment-methods-nav .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1rem;
        cursor: pointer;
        font-weight: 500;
    }
    .payment-methods-nav .nav-link.active {
        color: #4a148c;
        border-bottom-color: #4a148c;
        font-weight: 600;
    }
    .payment-method-content {
        display: none;
    }
    .payment-method-content.active {
        display: block;
    }
    .wallet-icons {
        display: flex;
        gap: 15px;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .wallet-icons img {
        height: 40px;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .wallet-icons img:hover {
        opacity: 1;
    }
    .razorpay-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card payment-card">
                <div class="card-header"><h2>Complete Your Payment</h2></div>
                <div class="card-body">
                    <div class="mb-4 summary-box">
                        <p><strong>Seva:</strong> {{ $sevaBooking->seva->name }} at {{ $sevaBooking->seva->temple->name }}</p>
                        <p class="total-amount"><strong>Total Amount:</strong> ₹{{ number_format($sevaBooking->amount, 2) }}</p>
                    </div>

                    {{-- Payment Method Tabs --}}
                    <div class="payment-methods-nav">
                        <div class="nav-link active" data-target="#card-payment">Card</div>
                        <div class="nav-link" data-target="#upi-payment">UPI</div>
                        <div class="nav-link" data-target="#netbanking-payment">Net Banking</div>
                        <div class="nav-link" data-target="#wallets-payment">Wallets</div>
                    </div>

                    {{-- Card Payment --}}
                    <div id="card-payment" class="payment-method-content active">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" class="form-control" placeholder="Enter Card Number">
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 form-group">
                                <label for="expiry">Expiry</label>
                                <input type="text" id="expiry" class="form-control" placeholder="MM/YY">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" class="form-control" placeholder="CVV">
                            </div>
                        </div>
                    </div>

                    {{-- UPI Payment --}}
                    <div id="upi-payment" class="payment-method-content text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=divyadarshan@okhdfcbank" alt="UPI QR Code" class="mx-auto">
                        <p class="my-3">Scan to pay or enter your UPI ID below.</p>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="yourname@upi">
                        </div>
                    </div>

                    {{-- Net Banking --}}
                    <div id="netbanking-payment" class="payment-method-content">
                        <div class="form-group">
                            <label for="bank-select">Select Your Bank</label>
                            <select id="bank-select" class="form-select">
                                <option>State Bank of India</option>
                                <option>HDFC Bank</option>
                                <option>ICICI Bank</option>
                                <option>Axis Bank</option>
                            </select>
                        </div>
                    </div>

                    {{-- Wallets --}}
                    <div id="wallets-payment" class="payment-method-content text-center">
                        <p>Select your preferred wallet to pay.</p>
                        <div class="wallet-icons">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/Amazon_Pay_logo.svg/2560px-Amazon_Pay_logo.svg.png" alt="Amazon Pay">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/PhonePe_Logo.svg/1200px-PhonePe_Logo.svg.png" alt="PhonePe">
                        </div>
                    </div>

                    {{-- This form handles the final submission --}}
                    <form action="{{ route('sevas.booking.confirm') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $sevaBooking->id }}">
                        <button type="submit" class="btn btn-success btn-lg w-100 btn-pay-now">Pay Now</button>
                    </form>

                    <div class="razorpay-footer">
                        <p>Secure Payments powered by Razorpay</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.payment-methods-nav .nav-link');
    const panes = document.querySelectorAll('.payment-method-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Deactivate all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));

            // Activate the clicked tab and its corresponding pane
            this.classList.add('active');
            const targetPane = document.querySelector(this.dataset.target);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});
</script>

