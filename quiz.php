<?php
$questions = [
  [
    "question" => "What do cats often do when you're working on a laptop?",
    "choices" => [
      "A. Offer to help type",
      "B. Bring you coffee",
      "C. Sit directly on the keyboard",
      "D. Hack into the Wi-Fi"
    ],
    "answer" => "C"
  ],
  [
    "question" => "Which of the following is a real dog breed?",
    "choices" => [
      "A. Spaghetti Retriever",
      "B. Pomeranian",
      "C. Mac-and-Cheese Hound",
      "D. Fuzzy Wuzzler"
    ],
    "answer" => "B"
  ],
  [
    "question" => "Why do dogs tilt their heads when you talk to them?",
    "choices" => [
      "A. They’re judging your grammar",
      "B. They're trying to understand",
      "C. They have neck cramps",
      "D. They're mocking you"
    ],
    "answer" => "B"
  ],
  [
    "question" => "What’s a hamster’s favorite workout?",
    "choices" => [
      "A. CrossFit",
      "B. Treadmill sprints",
      "C. Wheel running marathons",
      "D. Zumba"
    ],
    "answer" => "C"
  ],
  [
    "question" => "What unusual pet has been known to learn over 100 words?",
    "choices" => [
      "A. Goldfish",
      "B. Parrot",
      "C. Iguana",
      "D. Cat (when bribed)"
    ],
    "answer" => "B"
  ],
  [
    "question" => "Which of these is a legit reason cats knock things off tables?",
    "choices" => [
      "A. To summon demons",
      "B. For scientific gravity experiments",
      "C. Boredom and curiosity",
      "D. They hate interior design"
    ],
    "answer" => "C"
  ],
  [
    "question" => "Why do some dogs spin in circles before lying down?",
    "choices" => [
      "A. They're preparing for takeoff",
      "B. Ancient wolf behavior",
      "C. Confused GPS",
      "D. It's the dog version of fluffing a pillow"
    ],
    "answer" => "B"
  ],
  [
    "question" => "What’s a common sign that your fish thinks it’s a ninja?",
    "choices" => [
      "A. It disappears in plain sight",
      "B. It fakes its own death",
      "C. It blows stealth bubbles",
      "D. It swims upside down for sneak attacks"
    ],
    "answer" => "B"
  ],
  [
    "question" => "Which pet is known to sleep up to 20 hours a day?",
    "choices" => [
      "A. Koala (not a pet)",
      "B. Teenagers (also not pets)",
      "C. Cats",
      "D. Goldfish"
    ],
    "answer" => "C"
  ],
  [
    "question" => "If a dog brings you its leash, what is it most likely saying?",
    "choices" => [
      "A. \"Let's go for a walk!\"",
      "B. \"You forgot your fashion accessory.\"",
      "C. \"Take me to your leader.\"",
      "D. \"Tie me to the fridge.\""
    ],
    "answer" => "A"
  ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Pet Trivia Quiz</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <style>
    body {
      background-color: rgb(255, 255, 255);
      background: rgb(255, 242, 242);
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .quiz-container {
      max-width: 450px;
      width: 100%;
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      padding: 25px 20px;
      text-align: center;
      position: relative;
    }


    .question {
      font-size: 22px;
      margin-bottom: 50px;
      margin-top: 50px;
      color: #333;
    }

    .choices button {
      display: block;
      width: 100%;
      background-color: rgb(255, 255, 255);
      border: none;
      padding: 12px;
      border-radius: 10px;
      font-size: 16px;
      margin: 30px 0;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .choices button:hover {
      background-color: rgb(186, 0, 74);
      color: white;
      font-weight: bold;
    }

    .timer {
      position: absolute;
      top: 20px;
      right: 20px;
      background: #ff6961;
      color: white;
      padding: 8px 14px;
      border-radius: 30px;
      font-weight: bold;
    }

    .correct {
      background-color: rgb(170, 225, 108) !important;
      color: white;
    }

    .wrong {
      background-color: rgb(252, 175, 171) !important;
      color: white;
    }

    .next-btn {
      margin-top: 20px;
      padding: 10px 20px;
      font-weight: bold;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 10px;
      display: none;
      cursor: pointer;
    }

    .score-board {
      font-size: 18px;
      font-weight: bold;
      color: #ff6f61;
      margin-bottom: 10px;
      text-align: left;
    }

    #pet-animation {
      font-size: 36px;
      animation: bounce 0.8s ease infinite;
      margin: 10px 0;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    #feedback-card {
      margin-top: 20px;
      padding: 20px;
      border-radius: 15px;
      font-size: 18px;
      font-weight: bold;
      display: none;
    }

    #feedback-card.correct {
      background-color: #d4edda;
      color: #155724;
      border: 2px solid #c3e6cb;
    }

    #feedback-card.wrong {
      background-color: #f8d7da;
      color: #721c24;
      border: 2px solid #f5c6cb;
    }

    .button.adoptpet {
      background-color: #ff6f61;
      color: white;
      font-size: 18px;
      padding: 12px 30px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transition: background-color 0.3s ease, transform 0.2s ease;
      font-family: 'Comic Sans MS', cursive, sans-serif;
      margin-top: 20px;
    }

    .button.adoptpet:hover {
      background-color: #ff4c3b;
      transform: scale(1.05);
    }

    .button.adoptpet:active {
      transform: scale(0.98);
      background-color: #e74c3c;
    }
  </style>
</head>

<body>
  <div class="quiz-container">
    <div class="score-board">Score: <span id="score">0</span></div>
    <div id="pet-animation" style="display:none;">🐶🎉</div>
    <div class="timer" id="timer">15</div>
    <div class="question" id="question-text"></div>
    <div id="feedback-card"></div>
    <div class="choices" id="choices-container"></div>
    <button class="next-btn" id="next-btn">Next Question ➡️</button>
  </div>

  <script>
    const questions = <?php echo json_encode($questions); ?>;
    let currentQuestion = 0;
    let timer = 15;
    let countdown;

    const timerDisplay = document.getElementById("timer");
    const questionText = document.getElementById("question-text");
    const choicesContainer = document.getElementById("choices-container");
    const nextBtn = document.getElementById("next-btn");
    const feedbackCard = document.getElementById("feedback-card");
    const petAnim = document.getElementById("pet-animation");
    const scoreDisplay = document.getElementById("score");
    let score = 0;

    function loadQuestion() {
      timer = 15;
      timerDisplay.textContent = timer;
      nextBtn.style.display = "none";
      feedbackCard.style.display = "none";
      petAnim.style.display = "none";

      const q = questions[currentQuestion];
      questionText.textContent = q.question;

      choicesContainer.innerHTML = "";
      q.choices.forEach((choice) => {
        const btn = document.createElement("button");
        btn.textContent = choice;
        btn.onclick = () => checkAnswer(btn, choice[0]);
        choicesContainer.appendChild(btn);
      });

      countdown = setInterval(() => {
        timer--;
        timerDisplay.textContent = timer;
        if (timer === 0) {
          clearInterval(countdown);
          showCorrectAnswer();
        }
      }, 1000);
    }

    function checkAnswer(button, selected) {
      clearInterval(countdown);
      const correctAnswer = questions[currentQuestion].answer;
      const buttons = document.querySelectorAll(".choices button");

      buttons.forEach(btn => {
        const isCorrect = btn.textContent[0] === correctAnswer;
        btn.classList.add(isCorrect ? "correct" : "wrong");
        btn.disabled = true;
      });

      if (selected === correctAnswer) {
        feedbackCard.textContent = "Meow! You got it right! 🐱";
        feedbackCard.className = "correct";
        score += 1;
        scoreDisplay.textContent = score;
        petAnim.innerHTML = "🐶🎉";
      } else {
        feedbackCard.textContent = "Aww! You are wrong! 🐾";
        feedbackCard.className = "wrong";
        petAnim.innerHTML = "🙀💥";
      }

      petAnim.style.display = "block";
      feedbackCard.style.display = "block";
      nextBtn.style.display = "block";
    }

    function showCorrectAnswer() {
      const correctAnswer = questions[currentQuestion].answer;
      const buttons = document.querySelectorAll(".choices button");

      buttons.forEach(btn => {
        const isCorrect = btn.textContent[0] === correctAnswer;
        btn.classList.add(isCorrect ? "correct" : "wrong");
        btn.disabled = true;
      });

      feedbackCard.textContent = "Aww! You are wrong! 🐾";
      feedbackCard.className = "wrong";
      petAnim.innerHTML = "🙀💥";
      petAnim.style.display = "block";
      feedbackCard.style.display = "block";
      nextBtn.style.display = "block";
    }

    nextBtn.addEventListener("click", () => {
      currentQuestion++;
      if (currentQuestion < questions.length) {
        loadQuestion();
      } else {
        document.querySelector(".quiz-container").innerHTML = `
  <h2>QUIZ COMPLETE!! <br> Thank you for participating :></h2><br>
  <p>You’re pawsome! 🐾</p>
  <p>Your score: <strong>${score} / ${questions.length}</strong></p>
  <div style="font-size: 48px;">🏆🐕‍🦺🐾</div>
  <button class="button adoptpet" onclick="location.href='profile.php'">DONE</button>
`;

      }
    });

    loadQuestion();
  </script>
</body>

</html>