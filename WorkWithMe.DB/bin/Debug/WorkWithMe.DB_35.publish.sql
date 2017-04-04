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
PRINT N'Altering [dbo].[spUpdateUser]...';


GO
--Creates user and adds random salt to password.

ALTER PROCEDURE [dbo].[spUpdateUser]
	@Id uniqueidentifier,
	@Username nvarchar(40),
	@Password nvarchar(24),
	@FirstName nvarchar(50),
	@MiddleInitial nvarchar(2),
	@LastName nvarchar(50),
	@Zip int,
	@Address nvarchar(50),
	@City nvarchar(50),
	@State nvarchar(2),
	@IsAddressPrivate bit,
	@EmailAddress nvarchar(255),
	@Response nvarchar(100) output
AS
BEGIN
	SET NOCOUNT ON

	IF EXISTS (SELECT * FROM [dbo].[tblUser] WHERE Username = @Username and Id != @Id)
	BEGIN
		SET @Response='Username is taken.'
	    RETURN 0
	END
	IF EXISTS (SELECT * from [dbo].[tblUser] WHERE EmailAddress = @EmailAddress)
	BEGIN
	    SET @Response='Email address already exists.  Only one user per email please.'
	    RETURN 0
	END

    DECLARE @salt UNIQUEIDENTIFIER=NEWID()

    BEGIN TRY
	
		UPDATE [dbo].[tblUser] 
		SET Username = @Username,
			PasswordHash = HASHBYTES('SHA2_512', @Password+CAST(@salt AS NVARCHAR(36))),
			PasswordSalt = @salt,
			FirstName = @FirstName,
			MiddleInitial = @MiddleInitial,
			LastName = @LastName,
			EmailAddress = @EmailAddress,
			Zip = @Zip,
			Address = @Address,
			IsAddressPrivate = @IsAddressPrivate
		WHERE Id = @Id;

		IF NOT EXISTS(SELECT TOP 1 s.Id FROM tblState s WHERE s.StateName = @State)
		    INSERT INTO [dbo].[tblState] (Id, StateName) VALUES (NewId(), @State)
	
		IF NOT EXISTS(SELECT TOP 1 c.Id FROM tblCity c WHERE c.Cityname = @City)
		    INSERT INTO [dbo].[tblCity] (Id, CityName) VALUES (NewId(), @City)
		
		DECLARE @cityId UNIQUEIDENTIFIER = (SELECT c.Id FROM tblCity c WHERE c.CityName = @City);
		DECLARE @stateId UNIQUEIDENTIFIER = (SELECT s.Id FROM tblState s WHERE s.StateName = @State);

		IF NOT EXISTS (SELECT TOP 1 z.Id FROM tblZip z WHERE z.Id = @Zip)
		    INSERT INTO [dbo].[tblZip] (Id, CityId, StateId) VALUES (@Zip, @cityId, @stateId)
		
        SET @Response='Successfully updated user!'
		RETURN 1
    END TRY
    BEGIN CATCH
        SET @Response=ERROR_MESSAGE() 
		RETURN 0
    END CATCH
END
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