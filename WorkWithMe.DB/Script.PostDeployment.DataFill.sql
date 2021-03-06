﻿/*
Post-Deployment Script Template							
--------------------------------------------------------------------------------------
 This file contains SQL statements that will be appended to the build script.		
 Use SQLCMD syntax to include a file in the post-deployment script.			
 Example:      :r .\myfile.sql								
 Use SQLCMD syntax to reference a variable in the post-deployment script.		
 Example:      :setvar TableName MyTable							
               SELECT * FROM [$(TableName)]					
--------------------------------------------------------------------------------------
*/
delete from tblUser WHERE Username = 'Test'; 

declare @response nvarchar(100);

exec spCreateUser @Username = 'Test',
 @Password = 'Test', @FirstName = 'Testy', 
 @MiddleInitial = 'T', 
 @Lastname = 'McTesterson',
 @Zip = 54914,
 @City = 'Appleton',
 @State = 'WI',
 @Address = '1234 Street Rd',
 @IsAddressPrivate = 0,
 @EmailAddress = 'test@test.test',
 @Response = @response output;

 PRINT @response;

 delete from tblInviteStatus;

 insert into tblInviteStatus (Id, Description)
 values 
   (0,'Invited'),
   (1,'Declined'),
   (2,'Accepted'),
   (3,'Blocked');
 
 delete from tblGroupType;

 insert into tblGroupType (Id, Description)
 values
  (0, 'Personal'),
  (1, 'Company'),
  (2, 'Organization'),
  (3, 'Team');
