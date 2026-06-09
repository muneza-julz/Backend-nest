import { Controller, Get, Param, Query } from '@nestjs/common';
import { UsersService } from './users.service';

@Controller('users')
export class UsersController {
  constructor( private readonly usersService: UsersService){}

  @Get() // No path needed for query params
  findByQuery(@Query('id') id?: number) {
    if(id){
      return this.usersService.findByQuery(1);
    }
    return this.usersService.findAll("ENGINEER");
    
  }

  @Get('interns')
  findInterns() {
    return this.usersService.findInterns();
  }

  @Get(':id')
  findOne(@Param('id') id: number){
    return this.usersService.findOne(4);
  }  

}