CREATE PROCEDURE [dbo].[spCreateGroup]
	@Name nvarchar(50),
	@Description nvarchar(100),
	@GroupType int,
	@OwnerUserId uniqueidentifier,
	@OwnerGroupId uniqueidentifier null,
	@CanPostDefault bit,
	@CanInviteDefault bit,
	@CanDeleteDefault bit,
	@Response nvarchar(100) output
AS
	BEGIN
		IF EXISTS (SELECT * FROM [dbo].[tblGroup] WHERE Name = @Name)
		BEGIN
			SET @Response='Group name is taken.'
			RETURN 0
		END

		BEGIN TRY
			INSERT INTO [dbo].[tblGroup] (Id, Name, Description, GroupType, OwnerUserId, OwnerGroupId, GroupImgId, CanPostDefault, CanInviteDefault, CanDeleteDefault)
			values (NEWID(), @Name, @Description, @GroupType, @OwnerUserId, @OwnerGroupId, null, @CanPostDefault, @CanInviteDefault, @CanDeleteDefault)

			SET @Response='Successfully created group!'
			RETURN 1
		END TRY
		BEGIN CATCH
			SET @Response=ERROR_MESSAGE() 
			RETURN 0
		END CATCH
	END
