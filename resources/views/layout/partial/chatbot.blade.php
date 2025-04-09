<button class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-3 d-flex align-items-center justify-content-center" id="chatButton" style="width: 60px; height: 60px; z-index: 99999;">
    <i class="bi bi-chat-dots fs-4"></i>
</button>
<div class="position-fixed end-0 mb-4 me-3 d-none" id="chatBox" style="bottom: 65px; z-index: 9999;">
    <div class="card shadow-lg" style="width: 800px; height: 500px;">
        <div class="card-header bg-success text-white d-flex justify-content-between">
            <span>Trò chuyện với AI Restaurant</span>
            <button class="btn-close btn-close-white" id="closeChat"></button>
        </div>
        <div class="card-body overflow-auto" id="chatBody" style="height: 500px;">
            <div class="d-flex flex-column gap-2" id="chatbotContent">
                <div class="d-flex flex-column align-items-center justify-content-center text-center mt-10" id="chatbotIntro">
                    <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="" style="width: 200px; height: 200px;">
                    <p>Hỏi đáp thắc mắc của bạn với trợ lý nhà hàng Restaurant</p>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex">
            <input type="text" class="form-control shadow-none" placeholder="Nhập tin nhắn..." id="chatBotInput">
            <button class="btn btn-success ms-2" id="sendMessageChatbot">Gửi</button>
        </div>
    </div>
</div>