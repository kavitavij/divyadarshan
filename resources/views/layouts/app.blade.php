<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        /* Custom styles for the chatbot */
        #chat-icon {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background-color: #4a148c; /* Deep purple */
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.2s ease-in-out;
            z-index: 9998;
            overflow: hidden; /* Ensures the image inside is clipped to the circle */
        }
        #chat-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #chat-icon:hover {
            transform: scale(1.1);
        }
        #chat-notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 22px;
            height: 22px;
            background-color: #dc2626; /* Red */
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }
        #chat-widget {
            position: fixed;
            bottom: 100px;
            right: 25px;
            width: 350px;
            max-width: 90vw;
            height: 500px;
            max-height: 70vh;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: scale(0.5);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            transform-origin: bottom right;
            z-index: 9999;
        }
        #chat-widget.open {
            transform: scale(1);
            opacity: 1;
        }
        .chat-header {
            background-color: #4a148c;
            color: white;
            padding: 1rem;
            font-weight: bold;
            text-align: center;
            flex-shrink: 0; /* Prevents header from shrinking */
        }
        .chat-messages {
            flex-grow: 1; /* Allows this area to take up all available space */
            padding: 1rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .message {
            padding: 0.5rem 1rem;
            border-radius: 18px;
            max-width: 80%;
            line-height: 1.5;
        }
        .message.user {
            background-color: #3b82f6; /* Blue */
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }
        .message.bot {
            background-color: #f1f5f9; /* Light gray */
            color: #1e293b;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }
        .chat-input {
            border-top: 1px solid #e2e8f0;
            padding: 0.75rem;
            flex-shrink: 0; /* Prevents input from shrinking */
        }
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .typing-indicator span {
            width: 8px;
            height: 8px;
            background-color: #94a3b8;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }
    </style>
</head>
<body x-data="{ loginModal: false, modalView: 'login' }" :class="{ 'overflow-hidden': loginModal }" class="bg-gray-100 font-sans text-gray-800">
<div class="max-w-7xl mx-auto px-4">
    <script src="//unpkg.com/alpinejs" defer></script>

    <header class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
    <!-- Left: Logo -->
    <div class="flex-shrink-0">
      <a href="/" class="text-2xl font-bold text-blue-600">DivyaDarshan</a>
    </div>

    <!-- Middle: Navigation -->
    <nav class="flex items-center gap-8 text-sm font-medium text-gray-700">
      <a href="/" class="hover:text-blue-600">Home</a>
      <a href="/about" class="hover:text-blue-600">About</a>

      <!-- Temples Dropdown -->
      <div class="relative group">
        <button 
          aria-haspopup="true" 
          aria-expanded="false" 
          class="flex items-center gap-1 text-gray-700 hover:text-blue-600 px-3 py-2 rounded focus:outline-none"
          style="background:none; border:none; padding:0; margin:0; cursor:pointer;"
          id="templesDropdownBtn">
          <span>Temples</span>
          <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2" 
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <div 
          class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
          role="menu" 
          aria-labelledby="templesDropdownBtn">
          @foreach($allTemples as $temple)
            <a href="{{ route('temples.show', $temple->id) }}" 
               class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
               role="menuitem">
              {{ $temple->name }}
            </a>
          @endforeach
        </div>
      </div>

      <!-- Online Services Dropdown -->
      <div class="relative group">
          <button 
              aria-haspopup="true" 
              aria-expanded="false" 
              class="flex items-center gap-1 text-gray-700 hover:text-blue-600 px-3 py-2 rounded focus:outline-none"
              style="background:none; border:none; padding:0; margin:0; cursor:pointer;"
              id="servicesDropdownBtn">
              <span>Online Services</span>
              <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2" 
                   viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 9l-7 7-7-7"></path>
              </svg>
          </button>

          <div 
              class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
              role="menu" 
              aria-labelledby="servicesDropdownBtn">
              <a href="{{ route('booking.index') }}" 
                 class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                 role="menuitem">Darshan Booking</a>
              <a href="#" 
                 class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                 role="menuitem">Sevas</a>
              <a href="#" 
                 class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                 role="menuitem">Accommodation Booking</a>
              <a href="#" 
                 class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                 role="menuitem">Cab Booking</a>
          </div>
      </div>
      <a href="{{ route('ebooks.index') }}" class="hover:text-blue-600">eBooks</a>
    </nav>

    <!-- Right: Login Button -->
    <div class="flex-shrink-0">
    @guest
        <button @click="loginModal = true; modalView = 'login'" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Login
        </button>
    @else
        <div class="relative group">
            <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <span>{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div class="absolute hidden group-hover:block right-0 bg-white border rounded shadow-lg mt-1 min-w-max z-20">

                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                @endif

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                <a href="{{ route('profile.ebooks') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My eBooks</a>
                <a href="{{ route('profile.bookings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Bookings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    @endguest
</div>

  </div>
</header>

<!-- Swiper Slider -->
<div class="flex justify-center mt-4">
    <div class="swiper mySwiper" style="max-width: 400px;">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="{{ asset('images/temple1.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 1">
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('images/temple2.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 2">
            </div>
            <div class="swiper-slide">
                <img src="{{ asset('images/temple3.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 3">
            </div>
        </div>
    </div>
</div>

{{-- 🔷 Page Content --}}
<main class="py-10">
    @yield('content')
</main>

   {{-- 🔷 Footer --}}
<footer class="bg-gray-200 border-t py-4 text-sm text-gray-700">
    <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-between items-center gap-4">
        <div>&copy; {{ date('Y') }} DivyaDarshan. All rights reserved.</div>
        <div>
            Contact: +91-1234567890 |
            <a href="mailto:support@divyadarshan.com" class="text-blue-600 hover:underline">support@divyadarshan.com</a>
        </div>
        <div>
            <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">Terms & Condition</a> |
            <a href="{{ route('guidelines') }}" class="text-blue-600 hover:underline">Guidelines</a> |
            <a href="{{ route('complaint.form') }}" class="text-blue-600 hover:underline">Complaint</a>
        </div>
        <div class="flex gap-2">
            <a href="#" class="hover:text-blue-600">Facebook</a>
            <a href="#" class="hover:text-blue-600">Twitter</a>
            <a href="#" class="hover:text-blue-600">Instagram</a>
        </div>
    </div>
</footer>

</div>


{{-- 🔷 Swiper JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.mySwiper', {
            loop: true,
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>

<!-- Login/Register/Forgot Password Modal -->
<div 
    x-show="loginModal" 
    @click.away="loginModal = false" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    x-transition.opacity
    style="display: none;"
>
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md" @keydown.escape.window="loginModal = false">

      <!-- Login Form -->
      <template x-if="modalView === 'login'">
        <div>
          <h2 class="text-xl font-bold mb-4">Login</h2>
          <form method="POST" action="{{ route('login') }}">
              @csrf
              <div class="mb-4">
                  <label for="email" class="block text-gray-700 mb-1">Email</label>
                  <input id="email" name="email" type="email" required autofocus
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="mb-4">
                  <label for="password" class="block text-gray-700 mb-1">Password</label>
                  <input id="password" name="password" type="password" required
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="mb-4 flex items-center">
                  <input id="remember" name="remember" type="checkbox" class="mr-2">
                  <label for="remember" class="text-gray-700">Remember Me</label>
              </div>
              <div class="flex justify-between items-center mb-4">
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'forgot'">Forgot Password?</a>
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'register'">Click here to Register</a>
              </div>
              <a href="javascript:window.history.back();">GO Back </a>
              <div class="flex justify-end">
                  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Log In</button>
              </div>
          </form>
        </div>
      </template>

      <!-- Register Form -->
      <template x-if="modalView === 'register'">
        <div>
          <h2 class="text-xl font-bold mb-4">Register</h2>
          <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="mb-4">
                  <label for="name" class="block text-gray-700 mb-1">Name</label>
                  <input id="name" name="name" type="text" required autofocus
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="mb-4">
                  <label for="email" class="block text-gray-700 mb-1">Email</label>
                  <input id="email" name="email" type="email" required
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="mb-4">
                  <label for="password" class="block text-gray-700 mb-1">Password</label>
                  <input id="password" name="password" type="password" required
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="mb-4">
                  <label for="password_confirmation" class="block text-gray-700 mb-1">Confirm Password</label>
                  <input id="password_confirmation" name="password_confirmation" type="password" required
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="flex justify-between items-center mb-4">
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'login'">Already have an account? Login</a>
              </div>
              <div class="flex justify-end">
                  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                      Register
                  </button>
              </div>
          </form>
        </div>
      </template>

      <!-- Forgot Password Form -->
      <template x-if="modalView === 'forgot'">
        <div>
          <h2 class="text-xl font-bold mb-4">Forgot Password</h2>
          <form method="POST" action="{{ route('password.email') }}">
              @csrf
              <div class="mb-4">
                  <label for="email" class="block text-gray-700 mb-1">Email Address</label>
                  <input id="email" name="email" type="email" required autofocus
                         class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="flex justify-between items-center mb-4">
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'login'">Back to Login</a>
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'register'">Click here to Register</a>
              </div>
              <div class="flex justify-end">
                  <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                      Send Password Reset Link
                  </button>
              </div>
          </form>
        </div>
      </template>

    </div>
</div>

<!-- Chatbot Icon -->
<div id="chat-icon">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.837 8.837 0 01-4.445-1.272l-3.338 1.113a1 1 0 01-1.265-1.265l1.113-3.338A8.837 8.837 0 012 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM4.415 14.156a.636.636 0 01-.21-.49l-.33-1.103a6.834 6.834 0 00.916-2.013 1 1 0 111.916.539 4.833 4.833 0 01-1.423 1.88l1.103.33a.636.636 0 01.49.21 4.833 4.833 0 01-1.88 1.423z" clip-rule="evenodd" />
    </svg>
    <div id="chat-notification-badge">1</div>
</div>

<!-- Chatbot Widget -->
<div id="chat-widget">
    <div class="chat-header">DivyaDarshan Help Bot</div>
    <div class="chat-messages" id="chat-messages">
        <div class="message bot">Namaste! I am Lilly, your virtual assistant. How can I help you today?</div>
    </div>
    <div class="chat-input">
        <form id="chat-form" class="flex gap-2">
            <input type="text" id="user-input" class="flex-grow border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400" placeholder="Ask a question..." required>
            <button type="submit" class="bg-purple-600 text-white rounded-full p-2 hover:bg-purple-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.428A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
            </button>
        </form>
    </div>
</div>

{{-- All scripts go at the end of the body --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
@stack('scripts') {{-- For page-specific scripts like booking --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Swiper Initialization ---
    new Swiper('.mySwiper', {
        loop: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: { delay: 2500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
    });

    // --- Chatbot and Notification Logic ---
    const chatIcon = document.getElementById('chat-icon');
    const chatWidget = document.getElementById('chat-widget');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatMessages = document.getElementById('chat-messages');
    const chatNotificationBadge = document.getElementById('chat-notification-badge');

    const originalTitle = document.title;
    const notificationTitle = '(1) ' + originalTitle;
    let tabNotificationActive = true;

    if (sessionStorage.getItem('chatBotClicked')) {
        chatNotificationBadge.style.display = 'none';
        tabNotificationActive = false;
    }

    function handleVisibilityChange() {
        if (!tabNotificationActive) return;
        document.title = document.hidden ? notificationTitle : originalTitle;
    }
    document.addEventListener('visibilitychange', handleVisibilityChange);

    chatIcon.addEventListener('click', () => {
        chatWidget.classList.toggle('open');
        if (chatNotificationBadge.style.display !== 'none') {
            chatNotificationBadge.style.display = 'none';
            sessionStorage.setItem('chatBotClicked', 'true');
            tabNotificationActive = false;
            document.title = originalTitle;
        }
    });

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const userMessage = userInput.value.trim();
        if (userMessage) {
            addMessage(userMessage, 'user');
            userInput.value = '';
            callGeminiAPI(userMessage);
        }
    });

    function addMessage(text, sender) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', sender);
        messageElement.textContent = text;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTypingIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'typing-indicator';
        indicator.classList.add('message', 'bot');
        indicator.innerHTML = `<div class="typing-indicator"><span></span><span></span><span></span></div>`;
        chatMessages.appendChild(indicator);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    async function callGeminiAPI(prompt) {
        showTypingIndicator();
        const apiKey = "AIzaSyCRm02E9AhSIbZIrbQN-e_r8AWzbh1lD2w"; 
        const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=${apiKey}`;
        const systemPrompt = `You are a friendly and knowledgeable virtual assistant for "DivyaDarshan", a website dedicated to helping devotees with their pilgrimage needs in India. Your name is 'Lilly'. Your goal is to guide users to the correct section of the website, not to provide exhaustive information yourself. Keep your answers concise and focused on navigation.`;
        const chatHistory = [
            { role: "user", parts: [{ text: systemPrompt }] },
            { role: "model", parts: [{ text: "Namaste! I am ready to assist your users." }] },
            { role: "user", parts: [{ text: prompt }] }
        ];

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ contents: chatHistory }),
            });
            if (!response.ok) throw new Error(`API error: ${response.statusText}`);
            const result = await response.json();
            const botResponse = result.candidates[0].content.parts[0].text;
            removeTypingIndicator();
            addMessage(botResponse, 'bot');
        } catch (error) {
            console.error("Gemini API call failed:", error);
            removeTypingIndicator();
            addMessage("I'm sorry, I'm having trouble connecting right now. Please try again later.", 'bot');
        }
    }
});
</script>
</body>
</html>
