<template>
    <div class="container">
        <div
            class="d-flex justify-content-between mb-3 mt-3 align-items-center"
        >
            <h1 class="display-4 ">Projects</h1>
            <button
                class="btn btn-primary h-25"
                type="button"
                data-toggle="modal"
                data-target="#newProjectModal"
            >
                + New Project
            </button>
        </div>

        <hr />
        <div class="row">
            <div
                v-for="project in projects"
                class="col-md-4 col-lg-4 card p-3"
                v-bind:key="project.id"
            >
                <h3>{{ project.name }}</h3>
                <p>{{ project.desc }}</p>
                <button class="btn btn-primary mb-2">View Project</button>
                <button
                    class="btn btn-danger mb-2"
                    type="button"
                    data-toggle="modal"
                    data-target="#deleteProjectModal"
                    @click="selectProject(project)"
                >
                    Delete Project
                </button>
            </div>
        </div>

        <div
            class="modal fade"
            id="deleteProjectModal"
            tabindex="-1"
            aria-labelledby="deleteProjectModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProjectModalLabel">
                            Are you sure you want to delete this project?
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Remember you cannot recover this project once it's
                        deleted
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="deleteProject(selectedProject.id)"
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="newProjectModal"
            tabindex="-1"
            aria-labelledby="newProjectModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newProjectModalLabel">
                            Create New Project
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="createProject">
                            <div class="form-group">
                                <label for="projectName">Project Name</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="projectName"
                                    placeholder="Upgrade the rancor pit?"
                                    aria-describedby="emailHelp"
                                    v-model="project.name"
                                />
                            </div>
                            <div class="form-group">
                                <label for="projectName"
                                    >Project Description</label
                                >
                                <textarea
                                    class="form-control"
                                    id="projectName"
                                    rows="3"
                                    v-model="project.desc"
                                    placeholder="This project consists of adding an automatic rancor feeding machine"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            @click="clearForm()"
                        >
                            Close
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            @click="createProject"
                        >
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            projects: [],
            project: {
                name: "",
                desc: ""
            },
            selectedProject: {
                id: ""
            }
        };
    },

    created() {
        this.fetchProjects();
    },

    methods: {
        fetchProjects() {
            let vm = this;
            fetch("/api/projects")
                .then(res => res.json())
                .then(res => {
                    this.projects = res.data;
                })
                .catch(err => console.log(err));
        },
        selectProject(project) {
            let vm = this;
            this.selectedProject = project;
        },
        createProject() {
            fetch("/api/projects", {
                method: "POST",
                body: JSON.stringify(this.project),
                headers: {
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.clearForm();
                    $("#newProjectModal").modal("toggle");
                    $(".modal-backdrop").detach();
                    this.fetchProjects();
                })
                .catch(err => console.log(err));
        },
        clearForm() {
            this.project = {};
        },
        deleteProject(id) {
            fetch(`api/project/${id}`, { method: "delete" })
                .then(res => res.json())
                .then(() => {
                    $("#deleteProjectModal").modal("toggle");
                    $(".modal-backdrop").detach();

                    this.fetchProjects();
                })
                .catch(err => console.log(err));
        }
    }
};
</script>

<style scoped>
.newProject {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 2rem;
}
</style>
