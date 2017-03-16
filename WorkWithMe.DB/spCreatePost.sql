CREATE PROCEDURE [dbo].[spCreatePost]
	@PosterId uniqueidentifier,
	@TargetGroupId uniqueidentifier null,
	@Title nvarchar(50),
	@Content nvarchar(MAX),
	@IsSticky bit,
	@EventTimeStamp datetime null
AS
	BEGIN
		INSERT INTO [dbo].[tblPost] (Id, OwnerUserId, TargetGroupId, Title, Content, IsSticky, 
									 IsDeleted, TimeStamp, EventTimeStamp) values
		(NewId(),@PosterId,@TargetGroupId,@Title,@Content,@IsSticky,0, GETDATE(), @EventTimeStamp);
	END
RETURN @@ROWCOUNT;
