@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Asistent de carieră - Chatbot</div>

                <div class="card-body">
                    <div id="chat-messages" class="mb-3" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                        <div class="message bot">
                            <div class="message-content">
                                Bună ziua! Sunt asistentul virtual de carieră. Cum te pot ajuta astăzi?
                            </div>
                        </div>
                    </div>

                    <form id="chat-form">
                        <div class="input-group">
                            <input type="text" id="user-input" class="form-control" placeholder="Scrie întrebarea ta aici...">
                            <button class="btn btn-primary" type="submit">Trimite</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .message {
        margin-bottom: 15px;
        display: flex;
    }
    
    .message.user {
        justify-content: flex-end;
    }
    
    .message-content {
        padding: 10px;
        border-radius: 10px;
        max-width: 70%;
    }
    
    .message.bot .message-content {
        background-color: #f1f0f0;
    }
    
    .message.user .message-content {
        background-color: #007bff;
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const chatMessages = document.getElementById('chat-messages');
        
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = userInput.value.trim();
            if (message === '') return;
            
            // Adaugă mesajul utilizatorului în chat
            addMessage(message, 'user');
            
            // Trimite întrebarea la server
            fetch('{{ route('chatbot.ask') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ question: message })
            })
            .then(response => response.json())
            .then(data => {
                // Adaugă răspunsul chatbot-ului
                addMessage(data.answer, 'bot');
            })
            .catch(error => {
                console.error('Error:', error);
                addMessage('Îmi pare rău, a apărut o eroare. Te rog să încerci din nou.', 'bot');
            });
            
            userInput.value = '';
        });
        
        function addMessage(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', sender);
            
            const messageContent = document.createElement('div');
            messageContent.classList.add('message-content');
            messageContent.textContent = message;
            
            messageDiv.appendChild(messageContent);
            chatMessages.appendChild(messageDiv);
            
            // Scroll la ultimul mesaj
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
@endsection