/*
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
 @Response = @response output;

 PRINT @response;

 delete from tblInviteStatus;

 insert into tblInviteStatus (Id, Description)
 values 
   (0,'Invited'),
   (1,'Declined'),
   (2,'Accepted'),
   (3,'Blocked');

 delete from tblUserContact where OwnerUserId = '00000000-0000-0000-0000-000000000000';

 insert into tblUserContact (Id, OwnerUserId, TargetUserId, InviteStatusId)
 values (NewID(), '00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000001',2);

 delete from tblPost where OwnerUserId = '00000000-0000-0000-0000-000000000000';
 delete from tblPost where OwnerUserId = '00000000-0000-0000-0000-000000000001';

 insert into tblPost (Id, OwnerUserId, Title, Content, IsSticky, IsDeleted, TimeStamp, EventTimeStamp)
 values
 (NewId(),'00000000-0000-0000-0000-000000000000','Test Post from User 0','Stuff A',0,0, GETDATE(),null),
 (NewId(),'00000000-0000-0000-0000-000000000001','Test Post from User 1','Stuff B',0,0, GETDATE(),null);
GO
