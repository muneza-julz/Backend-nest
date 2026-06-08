import { Controller, Get, Param, Query } from '@nestjs/common';

@Controller('users')
export class UsersController {
  @Get() // No path needed for query params
  findByQuery(@Query('id') id?: string) {
    if (!id) {
      return [];
    }
    return { id };
  }

  @Get('interns')
  findInterns() {
    return {
      id: 1,
      name: "Scott McCall",
      age: 14
    };
  }

  @Get(':id')
  findOne(@Param('id') id: number) {
    if(id != 1)
        return { 
        id: id,
        name: "Prince",
        age:12
     }; // better to return an object
  }       

}