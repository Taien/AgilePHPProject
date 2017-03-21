CREATE PROCEDURE [dbo].[spGetPostsForUser]
	@UserId uniqueidentifier
AS
	SELECT * from [dbo].[tblPost] where OwnerUserId = @UserId
UNION
	SELECT * from [dbo].[tblPost] where OwnerUserId in (
		SELECT TargetUserId from [dbo].[tblUserContact] where OwnerUserId = @UserId and InviteStatusId = 2 -- 2 = accepted 
	)
ORDER BY TimeStamp desc
