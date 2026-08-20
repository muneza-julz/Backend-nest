import { Injectable } from '@nestjs/common';

@Injectable()
export class UsersService {
   private users = [
      { "id": 1, "name": "Leanne Graham", "email": "leanne@gmail.com", "role": "INTERN" },
      { "id": 2, "name": "Ervin Howell", "email": "ervin@gmail.com", "role": "INTERN" },
      { "id": 3, "name": "Clementine Bauch", "email": "bauch@gmail.com", "role": "ENGINEER" },
      { "id": 4, "name": "Patricia Lebsack", "email": "lebsack@gmail.com", "role": "ENGINEER" },
      { "id": 5, "name": "Chelsey Dietrich", "email": "dietrich@gmail.com", "role": "ADMIN" }
   ]

   findOne(id: number) {
      const user = this.users.find(user => user.id === id);
      return user;
   }
   findAll(role?: 'INTERN' | 'ENGINEER' | 'ADMIN') {
      if (role) {
         const users = this.users.filter(user => user.role === role);
         return users;
      }
      return this.users;
   }
   findByQuery(id: number) {
      const user = this.users.find(user => user.id === id);
      return user;
   }
   findInterns() {
      const interns = this.users.filter(user => user.role === "INTERN");
      return interns;
   }

}
