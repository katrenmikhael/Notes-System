//<!================initialize events=================>
if (localStorage.getItem("userId") == null) {
  window.location.replace("../login.html");
}

//<!================variables=================>
let userId = JSON.parse(localStorage.getItem("userId"));
getAllNotes(userId);
let noteConatiner = document.getElementById("notesContainer");
let noteText = document.getElementById("note");
let addBtn = document.getElementById("addNote");
let modal = document.querySelector(".modal");
let newNoteText = document.getElementById("editedNote");
let editBtn = document.getElementById("editNote");
let deleteBtn = document.getElementById("deleteNote");
let logOutBtn = document.getElementById("logOut");
let editedNoteId;
//<!================functions=================>
async function getAllNotes(userId) {
  const res = await fetch(
    `http://localhost:8080/notes%20API/getNotes.php?userId=${userId}`,
    { method: "GET" }
  );
  const response = await res.json();
  console.log(response);
  showNotes(response);
}

function showNotes(response) {
  let allNotes = ``;
  for (let note = 0; note < response.notes.length; note++) {
    allNotes += `
    <div class="col-4">
            <div
              class="card-body text-center p-3 border rounded-3 bg-white position-relative"
            >
              <div
                class="card-title bg-light w-100 position-absolute top-0 start-0 end-0 p-3"
              >
                <div class="row">
                  <div class="col-10 text-center">
                    <h5 class="card-title">Card title</h5>
                  </div>
                  <div class="col-2">
                    <div class="dropdown">
                      <i
                        class="dropdown-toggle fa-solid fa-ellipsis-vertical"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        Dropdown button
                      </i>
                      <ul class="dropdown-menu">
                        <li
                          class="d-flex justify-content-between align-items-center px-2"
                        >
                          <a class="dropdown-item bg-white" data-bs-toggle="modal" type="button" data-bs-target="#editNoteModal" href="#" 
                          onClick="sentNoteIdEdit(${response.notes[note]["id"]})">Edit</a
                          ><i
                            class="fa-regular fa-pen-to-square text-primary"
                          ></i>
                        </li>
                        <li
                          class="d-flex justify-content-between align-items-center px-2"
                        >
                          <a class="dropdown-item bg-white" href="#"  data-bs-toggle="modal" type="button" data-bs-target="#deleteNoteModal"
                           onClick="sentNoteIdDelete(${response.notes[note]["id"]})">Delete</a>
                          <i class="fa-solid fa-trash text-danger"></i>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <p class="card-text my-5">
                ${response.notes[note]["text"]}
              </p>
            </div>
          </div> 
          
     
    `;
  }
  noteConatiner.innerHTML = allNotes;
}
async function addNote() {
  const res = await fetch("http://localhost:8080/notes%20API/addNote.php", {
    method: "POST",
    body: JSON.stringify({ text: noteText.value, userId: userId }),
    headers: { "Content-type": "application/json; charset=UTF-8" },
  });
  const response = await res.json();
  $("#exampleModal").modal("hide");
  window.location.reload();
  console.log(response);
}
function sentNoteIdEdit(id) {
  editedNoteId = id;
}
async function editNote() {
  const res = await fetch("http://localhost:8080/notes%20API/editNote.php", {
    method: "PUT",
    headers: { "Content-type": "application/json" },
    body: JSON.stringify({
      text: newNoteText.value,
      noteId: editedNoteId,
    }),
  });
  const response = await res.json();
  $("#editNoteModal").modal("hide");
  window.location.reload();
  console.log(response);
}
function sentNoteIdDelete(id) {
  editedNoteId = id;
}
async function deleteNote() {
  const res = await fetch("http://localhost:8080/notes%20API/deleteNote.php", {
    method: "DELETE",
    headers: { "Content-type": "application/json" },
    body: JSON.stringify({
      noteId: editedNoteId,
    }),
  });
  const response = await res.json();
  $("#deleteNoteModal").modal("hide");
  window.location.reload();
  console.log(response);
}
function logOut() {
  localStorage.removeItem("userId");
}
//<!================Events=================>
addBtn.addEventListener("click", function (event) {
  event.preventDefault();
  addNote();
});
editBtn.addEventListener("click", function (event) {
  event.preventDefault();
  editNote();
});
deleteBtn.addEventListener("click", function (event) {
  event.preventDefault();
  deleteNote();
});
logOutBtn.addEventListener("click", function () {
  logOut();
});
