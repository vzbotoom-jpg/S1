# NexusAI

<p align="center">
  <img src="docs/screenshots/nexusai-chat.png" alt="NexusAI Chat Interface" width="100%">
</p>

<h1 align="center">NexusAI</h1>

<p align="center">
  <strong>AI Assistant Platform built for intelligent conversations, productivity, and modern AI workflows.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-UI-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/AI-Gemini-4285F4?style=for-the-badge&logo=google" alt="Gemini">
</p>

---

## About NexusAI

**NexusAI** is a modern AI chatbot platform designed to provide an intuitive interface for interacting with artificial intelligence.

The application combines a clean conversational interface with conversation history, dashboard functionality, AI model selection, and a responsive user experience.

NexusAI is designed as a foundation for building a larger AI platform with capabilities such as AI agents, external tools, automation, knowledge retrieval, and intelligent workflows.

---

## ✨ Features

### 🤖 AI Chat

Interact with an AI assistant through a modern conversational interface.

- Real-time conversation interface
- AI model selection
- New conversation
- Context-aware conversations
- Prompt suggestions
- Code assistance
- Data analysis
- General question answering

### 💬 Conversation Management

Manage previous conversations from a centralized interface.

- Conversation history
- Search conversations
- New chat
- Persistent conversations
- Conversation-based context

### 📊 Dashboard

Centralized dashboard for monitoring and accessing the application's core functionality.

Planned dashboard capabilities include:

- Usage statistics
- AI activity
- Conversation analytics
- Model usage
- System status
- Account information

### 🎨 Modern UI/UX

NexusAI uses a modern dark interface focused on readability and productivity.

Design characteristics:

- Dark interface
- Purple accent system
- Responsive layout
- Sidebar navigation
- Modern cards
- AI-focused interaction patterns
- Desktop and mobile friendly

### 🧠 AI Model Integration

NexusAI is designed to integrate with modern Large Language Models (LLMs).

The architecture can be extended to support:

- Google Gemini
- OpenAI
- Anthropic
- Local LLMs
- Custom AI models

---

# 🏗️ Architecture

NexusAI follows a modular application architecture.

```text
                    ┌─────────────────────┐
                    │      NexusAI UI     │
                    │   Chat / Dashboard  │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │    Laravel Backend  │
                    │                     │
                    │ Routes / Controllers│
                    │ Services / Models   │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │     AI Service      │
                    │                     │
                    │   Gemini / LLM API  │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │      Database       │
                    │                     │
                    │ Users / Chats /     │
                    │ Conversations      │
                    └─────────────────────┘
