﻿/*
Deployment script for WorkWithMe

This code was generated by a tool.
Changes to this file may cause incorrect behavior and will be lost if
the code is regenerated.
*/

GO
SET ANSI_NULLS, ANSI_PADDING, ANSI_WARNINGS, ARITHABORT, CONCAT_NULL_YIELDS_NULL, QUOTED_IDENTIFIER ON;

SET NUMERIC_ROUNDABORT OFF;


GO
:setvar DatabaseName "WorkWithMe"
:setvar DefaultFilePrefix "WorkWithMe"
:setvar DefaultDataPath ""
:setvar DefaultLogPath ""

GO
:on error exit
GO
/*
Detect SQLCMD mode and disable script execution if SQLCMD mode is not supported.
To re-enable the script after enabling SQLCMD mode, execute the following:
SET NOEXEC OFF; 
*/
:setvar __IsSqlCmdEnabled "True"
GO
IF N'$(__IsSqlCmdEnabled)' NOT LIKE N'True'
    BEGIN
        PRINT N'SQLCMD mode must be enabled to successfully execute this script.';
        SET NOEXEC ON;
    END


GO
PRINT N'Altering [dbo].[spGetPostsForUser]...';


GO
ALTER PROCEDURE [dbo].[spGetPostsForUser]
	@UserId uniqueidentifier
AS
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where OwnerUserId = @UserId and ReplyPostId is null
UNION
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where OwnerUserId in (
		SELECT TargetUserId 
		from [dbo].[tblUserContact] where OwnerUserId = @UserId and InviteStatusId = 2 -- 2 = accepted 
	) and ReplyPostId is null
UNION
	SELECT p.*, u.FirstName + 
	CASE WHEN u.MiddleInitial=null THEN ' ' 
	     ELSE ' ' + u.MiddleInitial + '. ' END + u.LastName AS 'OwnerFullName'
	from [dbo].[tblPost] p
	join [dbo].[tblUser] u on p.OwnerUserId = u.Id
	where OwnerUserId in (
		SELECT OwnerUserId 
		from [dbo].[tblUserContact] where TargetUserId = @UserId and InviteStatusId = 2 -- 2 = accepted 
	) and ReplyPostId is null
ORDER BY TimeStamp desc
GO
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

GO
PRINT N'Update complete.';


GO
